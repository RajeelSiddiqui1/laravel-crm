<?php

namespace App\Http\Controllers;

use App\Models\OnwerTask;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\TeamLead;
use App\Models\Department;
use App\Models\Employee;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamLeadController extends Controller
{
    function resgisterview()
    {
        $departments = Department::all();
        return view('team_lead.register', compact('departments'));
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:team_leads',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            ToastMagic::error('Validation failed. Please check your input.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $manager = new TeamLead();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        $manager->password = bcrypt($request->password);
        $manager->department_id = $request->department_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/team_leads'), $imageName);
            $manager->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = @file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/team_leads/$imageName"), $imageContent);
                $manager->image = $imageName;
            }
        }

        if ($manager->save()) {
            $token = Str::random(64);
            $manager->login_token = $token;
            $manager->save();

            $loginLink = route('team_lead.token.login', ['token' => $token]);
            Mail::to($manager->email)->send(new AuthMail($manager, $loginLink));

            ToastMagic::success('Team Lead registered successfully. Check your email for login link.');
            return redirect()->route('welcome');
        }

        ToastMagic::error('Failed to register Team Lead.');
        return redirect()->back()->withInput();
    }

    function loginview()
    {
        return view('team_lead.login');
    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            ToastMagic::error('Validation failed. Please check your input.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('team_lead')->attempt($credentials)) {
            ToastMagic::success('Login successful!');
            return redirect()->route('team_lead.home');
        }

        ToastMagic::error('Invalid login credentials.');
        return redirect()->back()->withInput();
    }

    function tokenLogin($token)
    {
        $team_lead = TeamLead::where('login_token', $token)->first();

        if (!$team_lead) {
            ToastMagic::error('Invalid or expired login token.');
            return redirect()->route('team_lead.login');
        }

        Auth::guard('team_lead')->login($team_lead);
        $team_lead->login_token = null;
        $team_lead->save();

        ToastMagic::success('Logged in successfully via token!');
        return redirect()->route('team_lead.home');
    }

    function logout()
    {
        Auth::guard('team_lead')->logout();
        ToastMagic::success('Logged out successfully!');
        return redirect()->route('team_lead.login');
    }

    function home()
    {
        $teamLead = Auth::guard('team_lead')->user();
        $departmentId = $teamLead->department_id;
        $employees = Employee::where('department_id', $departmentId)->get();
        $tasks = OnwerTask::with(['teamLead', 'department'])
            ->where('team_lead_id', $teamLead->id)
            ->get();
        return view('team_lead.home', compact('tasks', 'employees'));
    }

    function profile_view()
    {
        $manager = Auth::guard('team_lead')->user();
        return view('team_lead.profile', compact('manager'));
    }

    function updateProfile(Request $request)
    {
        /** @var \App\Models\TeamLead $employee */
        $employee = Auth::guard('team_lead')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_leads,email,' . $employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;

        if ($request->hasFile('image')) {
            $oldImage = public_path('images/team_leads/' . $employee->image);

            if ($employee->image && file_exists($oldImage)) {
                @unlink($oldImage);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/team_leads'), $imageName);
            $employee->image = $imageName;
        }

        if ($request->filled('password')) {
            $employee->password = bcrypt($request->password);
        }

        $employee->save();

        ToastMagic::success('Profile updated successfully!');
        return back();
    }

    public function manager_tasks()
    {
        $teamLead = Auth::guard('team_lead')->user();

        $departmentId = $teamLead->department_id;

        $employees = Employee::where('department_id', $departmentId)->get();

        $tasks = OnwerTask::with(['teamLead', 'department'])
            ->where('team_lead_id', $teamLead->id)
            ->get();

        return view('team_lead.manager_tasks', compact('tasks', 'employees'));
    }

    public function assignEmployees(Request $request, OnwerTask $task)
    {
        $request->validate([
            'employee_id' => 'nullable|array',
            'employee_id.*' => 'exists:employees,id',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        if ($task->team_lead_id !== $teamLead->id) {
            ToastMagic::error('Unauthorized action. This task is not assigned to you.');
            abort(403, 'Unauthorized action. This task is not assigned to you.');
        }

        $newIds = $request->input('employee_id', []);

        $existingIds = $task->employee_id ? explode(',', $task->employee_id) : [];

        $mergedIds = array_unique(array_merge($existingIds, $newIds));

        foreach ($mergedIds as $employeeId) {
            $employee = Employee::find($employeeId);
            if (!$employee || $employee->department_id !== $teamLead->department_id) {
                ToastMagic::error('You can only assign employees from your own department.');
                return back()->with('error', 'You can only assign employees from your own department.');
            }
        }

        $task->employee_id = implode(',', $mergedIds);
        $task->save();

        ToastMagic::success('Employees assigned successfully!');
        return back();
    }

    public function updateStatus(Request $request, OnwerTask $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        if ($task->team_lead_id !== $teamLead->id) {
            ToastMagic::error('Unauthorized action. This task is not assigned to you.');
            return redirect()->back()->withInput();
        }

        $task->status = $request->status;
        $task->save();

        ToastMagic::success('Task status updated successfully!');

        return redirect("/team-lead/manager-tasks");
    }

    function manager_tasks_detail($id)
    {
        $task = OnwerTask::findOrFail($id);
        return view('team_lead.manager_tasks_detail', compact('task'));
    }


    public function subtask_view($id)
    {
        $subtask = Subtask::with('employee', 'task')->findOrFail($id);
        return view('team_lead.subtask_view', compact('subtask'));
    }

    public function subtask_edit($id)
    {
        $subtask = Subtask::findOrFail($id);
        return view('team_lead.subtask_edit', compact('subtask'));
    }




    public function subtask_create($taskId)
    {
        $task = OnwerTask::findOrFail($taskId);
        $teamLead = Auth::guard('team_lead')->user();

        $assignedEmployees = Employee::whereIn('id', explode(',', $task->employee_id))
            ->where('department_id', $teamLead->department_id)
            ->get();

        return view('team_lead.create_subtask', compact('task', 'assignedEmployees'));
    }

    public function subtask_store(Request $request)
    {
        $request->validate([
            'owner_task_id' => 'required|exists:owner_tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_employee_id' => 'required|exists:employees,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $teamLead = Auth::guard('team_lead')->user();
        $task = OnwerTask::findOrFail($request->owner_task_id);

        if ($task->team_lead_id !== $teamLead->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $employee = Employee::findOrFail($request->assigned_employee_id);
        if ($employee->department_id !== $teamLead->department_id) {
            return back()->with('error', 'Invalid employee department.');
        }

        Subtask::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_employee_id' => $employee->id,
            'owner_task_id' => $task->id,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('team_lead.subtask.list', $task->id)->with('success', 'Subtask created');
    }


    public function subtask_update(Request $request, $id)
    {
        $request->validate([
            'owner_task_id' => 'required|exists:owner_tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_employee_id' => 'required|exists:employees,id',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
        ]);

        $teamLead = Auth::guard('team_lead')->user();
        $task = OnwerTask::findOrFail($request->owner_task_id);

        if ($task->team_lead_id !== $teamLead->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $employee = Employee::findOrFail($request->assigned_employee_id);
        if ($employee->department_id !== $teamLead->department_id) {
            return back()->with('error', 'Invalid employee department.');
        }

        $subtask = Subtask::findOrFail($id);

        $subtask->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_employee_id' => $employee->id,
            'owner_task_id' => $task->id,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('team_lead.subtask.list', $task->id)->with('success', 'Subtask updated');
    }

    public function subtask_list($id)
    {
        $task = OnwerTask::with('subtasks.employee')->findOrFail($id);
        return view('team_lead.subtask_list', compact('task'));
    }
}
