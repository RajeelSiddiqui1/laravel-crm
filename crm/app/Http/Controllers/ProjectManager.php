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
use App\Models\Notification;
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
            return redirect()->back()->witheError($validator)->withInput();
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

            session()->flash('success_swal', 'Project Manager registered successfullyly.');
            return redirect()->route('welcome');
        }
        session()->flash('error_swal', 'Failed to register Project Manager.');
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
            return redirect()->back()->witherror_swals($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('project_manager')->attempt($credentials)) {
            return redirect()->route('project_manager.home')->with('success_swal', 'Login successfully');
        }

        return redirect()->back()->with('error_swal', 'Invalid login credentials');
    }

    function tokenLogin($token)
    {
        $manager = ModelsProjectManager::where('login_token', $token)->first();

        if (!$manager) {
            return redirect()->route('project_manager.login')->with('error_swal', 'Invalid or expired login token.');
        }

        Auth::guard('project_manager')->login($manager);
        $manager->login_token = null;
        $manager->save();

        return redirect()->route('project_manager.home')->with('success_swal', 'Logged in successfullyly via token.');
    }


    function logout()
    {
        Auth::guard('project_manager')->logout();
        return redirect()->route('project_manager.login')->with('success_swal', 'Logged out successfullyly');
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

        return back()->with('success_swal', 'Profile updated successfullyly!');
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
            session()->flash('error_swal', 'Team Lead data not fetched.');
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
        if ($task->save()) {
            $team_lead = TeamLead::find($task->team_lead_id);
            Mail::to($team_lead->email)->send(new AssignedTeamLeaderTask($task));
        }

        return back()->with('success_swal', 'Team Lead assigned successfullyly!');
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

    function manager_task_list()
    {
        $manager = Auth::guard('project_manager')->user();
        $tasks = OnwerTask::where('project_manger_task', $manager->id)->get();
        return view('project_manager.my_task', compact('tasks'));
    }

    function my_task_detail($id)
    {
        $task = OnwerTask::findOrFail($id);
        return view('project_manager.my_task_detail', compact('task'));
    }


    function create_my_task()
    {
        $project_manager = Auth::guard('project_manager')->user();

        // Fetch departments assigned to the logged-in manager
        $departments = $project_manager->departments; // uses the belongsToMany relation

        // Get team leads belonging to those departments
        $team_leads = TeamLead::whereIn('department_id', $departments->pluck('id'))->get();

        return view('project_manager.create_my_task', compact('team_leads', 'departments'));
    }

    function store_my_task(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'client_name' => 'required',
            'description' => 'required',
            'client_email' => 'required|email',
            'client_contact' => 'required',
            'department_id' => 'required|exists:departments,id',
            'team_lead_id' => 'required|exists:team_leads,id',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->witherror_swals($validator)->withInput();
        }

        $task = new OnwerTask();
        $task->name = $request->name;
        $task->client_name = $request->client_name;
        $task->description = $request->description;
        $task->client_email = $request->client_email;
        $task->client_contact = $request->client_contact;
        $task->department_id = $request->department_id;
        $task->team_lead_id = $request->team_lead_id;
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->status = 'pending';
        $task->project_manager_id =  Auth::guard('project_manager')->id();
        $task->project_manger_task =  Auth::guard('project_manager')->id();

        if ($task->save()) {
            $team_leads = TeamLead::where('department_id', $request->department_id)->get();

            foreach ($team_leads as $teamLead) {
                Notification::create([
                    'title' => 'New Task Created',
                    'message' => 'A new task "' . $task->name . '" has been assigned in your department.',
                    'user_id' => $teamLead->id,
                    'user_type' => 'team_lead',
                ]);
                return redirect()->route('project_manager.mytask')->with('success_swal', 'Task created and team leads notified.');
            }
            return redirect()->route('project_manager.mytask')->with('error_swal', 'Task creation failed.');
        }
    }

    function mytask_edit($id)
    {
        $task = OnwerTask::findOrFail($id);
        $departments = Department::with('teamLeads')->get(); // use correct relationship name
        $team_leads = TeamLead::all(); // get team leads if needed for dropdown
        return view('project_manager.edit_my_task', compact('task', 'departments', 'team_leads'));
    }


    function mytask_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'client_name' => 'required',
            'description' => 'required',
            'client_email' => 'required|email',
            'client_contact' => 'required',
            'department_id' => 'required|exists:departments,id',
            'team_lead_id' => 'required|exists:team_leads,id',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->witherror_swals($validator)->withInput();
        }

        $task = OnwerTask::findOrFail($id);
        $task->name = $request->name;
        $task->client_name = $request->client_name;
        $task->description = $request->description;
        $task->client_email = $request->client_email;
        $task->client_contact = $request->client_contact;
        $task->department_id = $request->department_id;
        $task->team_lead_id = $request->team_lead_id;
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->status = 'pending';

        if ($task->save()) {
            $team_leads = TeamLead::where('department_id', $request->department_id)->get();

            foreach ($team_leads as $teamLead) {
                Notification::create([
                    'title' => 'Task Updated',
                    'message' => 'Task "' . $task->name . '" has been updated in your department.',
                    'user_id' => $teamLead->id,
                    'user_type' => 'team_lead',
                ]);
            }

            return redirect()->route('project_manager.mytask')->with('success_swal', 'Task updated and team leads notified.');
        }

        return redirect()->route('project_manager.mytask')->with('error_swal', 'Task update failed.');
    }
    function my_task_destroy($id)
    {
        $task = OnwerTask::findOrFail($id);


        $taskName = $task->name;
        $teamLeadId = $task->team_lead_id;

        $task->delete();


        $team_lead = TeamLead::find($teamLeadId);
        if ($team_lead) {
            Notification::create([
                'title' => 'Task Deleted',
                'message' => 'Task "' . $taskName . '" has been deleted in your department.',
                'user_id' => $team_lead->id,
                'user_type' => 'team_lead',
            ]);
        }

        return redirect()->route('project_manager.mytask')->with('success_swal', 'Task deleted successfullyly.');
    }
}
