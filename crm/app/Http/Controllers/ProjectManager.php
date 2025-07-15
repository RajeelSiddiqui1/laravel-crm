<?php

namespace App\Http\Controllers;

use App\Mail\AssignedTeamLeaderTask;
use App\Models\Department;
use App\Models\OnwerTask;
use App\Models\ProjectManager as ModelsProjectManager;
use App\Models\ProjectOwner;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\TeamLead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification;


class ProjectManager extends Controller
{
    function resgisterview()
    {
        $departments = Department::all();
        return view('project_manager.register', compact('departments'));
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:project_managers',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $manager = new ModelsProjectManager();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        $manager->password = bcrypt($request->password);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/project_managers'), $imageName);
            $manager->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/project_managers/$imageName"), $imageContent);
                $manager->image = $imageName;
            }
        }

        if ($manager->save()) {
            $manager->departments()->attach($request->department_ids);
            $token = Str::random(64);
            $manager->login_token = $token;
            $manager->save();

            $loginLink = route('project_manager.token.login', ['token' => $token]);
            Mail::to($manager->email)->send(new AuthMail($manager, $loginLink));

            session()->flash('success', 'Project Manager registered successfully.');
            return redirect()->route('welcome');
        }
        session()->flash('error', 'Failed to register Project Manager.');
        return redirect()->back()->withInput();
    }

    function loginview()
    {
        return view('project_manager.login');
    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('project_manager')->attempt($credentials)) {
            return redirect()->route('project_manager.home')->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    function tokenLogin($token)
    {
        $manager = ModelsProjectManager::where('login_token', $token)->first();

        if (!$manager) {
            return redirect()->route('project_manager.login')->with('error', 'Invalid or expired login token.');
        }

        Auth::guard('project_manager')->login($manager);
        $manager->login_token = null;
        $manager->save();

        return redirect()->route('project_manager.home')->with('success', 'Logged in successfully via token.');
    }


    function logout()
    {
        Auth::guard('project_manager')->logout();
        return redirect()->route('project_manager.login')->with('success', 'Logged out successfully');
    }

    function home()
    {
        return view('project_manager.home');
    }

    function profile_view()
    {
        $manager = Auth::guard('project_manager')->user();
        return view('project_manager.profile', compact('manager'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\ProjectManager $employee */
        $employee = Auth::guard('project_manager')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:project_managers,email,' . $employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;


        if ($request->hasFile('image')) {
            $oldImage = public_path('images/project_managers/' . $employee->image);

            if ($employee->image && file_exists($oldImage)) {
                @unlink($oldImage);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/project_managers'), $imageName);
            $employee->image = $imageName;
        }

        $employee->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function onwertask()
    {
        $pm = Auth::guard('project_manager')->user();

        $departmentIds = $pm->departments->pluck('id')->toArray();

        $teamLeads = TeamLead::whereIn('department_id', $departmentIds)->get();

        $tasks = OnwerTask::with(['teamLead', 'department'])
            ->where('project_manager_id', $pm->id)
            ->get();

        if ($teamLeads->isEmpty()) {
            session()->flash('error', 'Team Lead data not fetched.');
        }

        return view('project_manager.owner_tasks', compact('tasks', 'teamLeads'));
    }

    public function assignTeamLead(Request $request, OnwerTask $task)
    {
        $request->validate(['team_lead_id' => 'required|exists:team_leads,id']);

        $pm = Auth::guard('project_manager')->user();
        $teamLead = TeamLead::find($request->team_lead_id);

        if ($task->project_manager_id !== $pm->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($teamLead->department_id !== $task->department_id) {
            abort(403, 'The selected team lead does not belong to the same department as the task.');
        }

        if (!$pm->departments->contains('id', $teamLead->department_id)) {
            abort(403, 'You cannot assign a team lead from a department you are not associated with.');
        }

        $task->team_lead_id = $teamLead->id;
        if($task->save()){
            $team_lead = TeamLead::find($task->team_lead_id);
            Mail::to($team_lead->email)->send(new AssignedTeamLeaderTask($task));
        }

        return back()->with('success', 'Team Lead assigned successfully!');
    }
    function onwertask_detail($id)
    {
        $task = OnwerTask::findOrFail($id);
        return view('project_manager.owner_task_detail', compact('task'));
    }





    public function notifications()
    {
        $manager = Auth::guard('project_manager')->user();
        $notifications = $manager->notifications()->latest()->get();

        $manager->unreadNotifications->markAsRead(); // mark all unread as read

        return view('project_manager.notifications', compact('notifications'));
    }


    public function viewNotification($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        if ($notification->notifiable_id !== Auth::guard('project_manager')->id()) {
            abort(403);
        }

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return redirect()->route('project_manager.tasks');
    }

    function subtask()
    {

        $subtasks = Subtask::with('employee')->get();
        return view('project_manager.subtask', compact('subtasks'));
    }
}
