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
use App\Models\Account;
use App\Models\Notification;
use App\Models\SharedTask;
use App\Models\TeamLead;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;

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
        $manager->department_ids = $request->department_ids; // ✅ save as JSON

        // Handle image
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

        // Save and send login link
        if ($manager->save()) {
            $token = Str::random(64);
            $manager->login_token = $token;
            $manager->save();

            $loginLink = route('project_manager.token.login', ['token' => $token]);
            Mail::to($manager->email)->send(new AuthMail($manager, $loginLink));

            session()->flash('success_swal', 'Project Manager registered successfully.');
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

    public function home()
    {
        $manager = Auth::guard('project_manager')->user();

        $tasks = SharedTask::with(['task.department', 'task.teamLead'])
            ->where('assigned_to', $manager->id)
            ->latest()
            ->get()
            ->pluck('task');

        return view('project_manager.home', compact('tasks'));
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
        $manager = Auth::guard('project_manager')->user();

        $otherManagers = ModelsProjectManager::where('id', '!=', $manager->id)
            ->pluck('name', 'id');

        // Fetch tasks and apply department name transformation
        $tasks = OnwerTask::with(['department', 'teamLead'])
            ->where('project_manager_id', $manager->id)
            ->get()
            ->map(function ($task) {
                if ($task->department && $task->department->name === 'Call Center') {
                    $task->department->name = 'Call operator';
                }
                return $task;
            });

        // Fetch team leads based on manager's department_ids
        $teamLeads = TeamLead::whereIn('department_id', $manager->department_ids ?? [])->get();

        if ($teamLeads->isEmpty()) {
            session()->flash('error_swal', 'Team Lead data not fetched.');
        }

        return view('project_manager.owner_tasks', compact('tasks', 'teamLeads', 'otherManagers'));
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

        // Check if team lead's department_id is in the manager's department_ids array
        $managerDepartmentIds = $pm->department_ids ?? [];
        if (!in_array($teamLead->department_id, $managerDepartmentIds)) {
            abort(403, 'You cannot assign a team lead from a department you are not associated with.');
        }

        $task->team_lead_id = $teamLead->id;
        if ($task->save()) {
            $team_lead = TeamLead::find($task->team_lead_id);
            Mail::to($team_lead->email)->send(new AssignedTeamLeaderTask($task));
        }

        return back()->with('success_swal', 'Team Lead assigned successfully!');
    }

    public function updateStatus2(Request $request, $id)
    {
        $request->validate(['status2' => 'required|in:approved,rejected,lated,pending']);
        OnwerTask::findOrFail($id)->update(['status2' => $request->status2]);
        return redirect()->back()->with('success_swal', 'Status updated');
    }

    // TaskController.php  (or wherever your project-manager methods live)
    public function updateStatus3(Request $request, $id)
    {
        $request->validate([
            'status3' => 'required|in:pending,approved,rejected,lated'
        ]);

        $task = OnwerTask::findOrFail($id);
        $task->status3 = $request->status3;
        $task->save();

        if (!$task->save()) {
            return redirect()->back()->with('error_swal', 'Status3 not updated successfully.');
        }

        return redirect()->back()->with('success_swal', 'Status3 updated successfully.');
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

 
public function manager_task_list()
{
    $currentManager = Auth::guard('project_manager')->user();

    // For Share Dropdown
    $otherManagers = ModelsProjectManager::where('id', '!=', $currentManager->id)
        ->pluck('name', 'id');

    // Fetch tasks with required relations
    $tasks = OnwerTask::with(['department', 'teamLead', 'account'])
        ->where('project_manager_task', $currentManager->id)
        ->get()
        ->filter(function ($task) use ($currentManager) {
            $isCallCenter = $task->department && $task->department->name === 'Call Center';

            // For call center: show only if shared
            if ($isCallCenter) {
                return SharedTask::where('owner_task_id', $task->id)
                    ->where('assigned_by', $currentManager->id)
                    ->exists();
            }

            // For other departments: show all
            return true;
        })
        ->map(function ($task) {
            // Rename "Call Center" to "Call Operator"
            if ($task->department && $task->department->name === 'Call Center') {
                $task->department->name = 'Call operator';
            }
            return $task;
        });

    return view('project_manager.my_task', compact('tasks', 'otherManagers'));
}



function my_task_detail($id)
{
    $task = OnwerTask::findOrFail($id);
    return view('project_manager.my_task_detail', compact('task'));
}




public function shareTask(Request $request)
{
    $validated = $request->validate([
        'task_id'       => 'required|exists:owner_tasks,id',
        'department_id' => 'required|exists:departments,id',
        'assigned_to'   => 'required|exists:project_managers,id',
    ]);

    $currentManager = auth()->guard('project_manager')->user();
    $task           = OnwerTask::findOrFail($validated['task_id']);

    // Insert or update the share row
    SharedTask::updateOrCreate(
        [
            'owner_task_id' => $validated['task_id'],
            'assigned_by'   => $currentManager->id,
            'assigned_to'   => $validated['assigned_to'],
        ],
        [
            'department_id' => $validated['department_id'],
        ]
    );

    // Notify the manager who receives the share
    Notification::create([
        'title'     => 'Task Shared With You',
        'message'   => 'Task "' . $task->name . '" has been shared by ' . $currentManager->name . '.',
        'user_id'   => $validated['assigned_to'],
        'user_type' => 'project_manager',
    ]);

    return response()->json(['success' => true, 'message' => 'Task has been shared']);
}


public function create_my_task()
{
    $manager = Auth::guard('project_manager')->user();
    $deptIds = $manager->department_ids;

    $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
    $team_leads = TeamLead::whereIn('department_id', $deptIds)->get(['id', 'name', 'department_id']);

    $isAccounts = Department::whereIn('id', $deptIds)->where('name', 'Accounts')->exists();
    $accounts = $isAccounts ? Account::take(1)->get(['id', 'clientname', 'email', 'due_date', 'nature_of_business', 'attachments','priority']) : collect();

    return view('project_manager.create_my_task', compact('departments', 'team_leads', 'isAccounts', 'accounts'));
}

public function store_my_task(Request $request)
{
    $manager = Auth::guard('project_manager')->user();

    $isAccounts = Department::whereIn('id', $manager->department_ids)
        ->where('name', 'Accounts')
        ->exists();

    $rules = [
        'department_id' => 'required|exists:departments,id',
        'team_lead_id' => 'required|exists:team_leads,id',
    ];

    if ($isAccounts) {
        $rules = array_merge($rules, [
            'clientname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'due_date' => 'required|date',
            'nature_of_business' => 'required|string',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,csv,txt,rtf,mp4,avi,mov,webm,mp3,wav,ogg,zip,rar,7z,js,html,css,php,py,java,c,cpp,dart|max:20480',
            'priority' => 'string',
        ]);
    }

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $account = null;
    if ($isAccounts) {
        $account = new Account();
        $account->clientname = $request->clientname;
        $account->email = $request->email;
        $account->phone = $request->phone;
        $account->due_date = $request->due_date;
        $account->nature_of_business = $request->nature_of_business;
        $account->priority = $request->priority;

        if ($request->hasFile('attachments')) {
            try {
                $file = $request->file('attachments');
                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'public_id' => 'manager_task/' . uniqid() . '_' . $file->getClientOriginalName(),
                    'resource_type' => 'auto',
                ]);
                $account->attachments = $uploaded['secure_url'];
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed: ' . $e->getMessage());
                return response()->json(['error' => 'File upload failed'], 500);
            }
        }

        $account->save();
    }

    $task = new OnwerTask();
    $task->priority = $request->priority;
    $task->department_id = $request->department_id;
    $task->team_lead_id = $request->team_lead_id;
    $task->status = 'pending';
    $task->project_manager_id = $manager->id;
    $task->project_manager_task = $manager->id;

    if ($isAccounts && $account) {
        $task->account_id = $account->id;
    }

    $task->save();

    foreach (TeamLead::where('department_id', $request->department_id)->get() as $tl) {
        Notification::create([
            'title' => 'New Task Created',
            'message' => 'A new task has been assigned in your department.',
            'user_id' => $tl->id,
            'user_type' => 'team_lead',
        ]);
    }

    return redirect()->route('project_manager.mytask')->with('success_swal', 'Task created and team leads notified.');
}


    public function mytask_edit($id)
{
    $task = OnwerTask::with(['department', 'teamLead', 'account'])->findOrFail($id);
    $manager = Auth::guard('project_manager')->user();
    $deptIds = $manager->department_ids;

    $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
    $team_leads = TeamLead::whereIn('department_id', $deptIds)->get(['id', 'name', 'department_id']);

    return view('project_manager.edit_my_task', compact('task', 'departments', 'team_leads'));
}

public function mytask_update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
       
        'client_name' => 'required',
        
        'client_email' => 'required|email',
        'client_contact' => 'required',
        'department_id' => 'required|exists:departments,id',
        'team_lead_id' => 'required|exists:team_leads,id',
      
        'priority' => 'required|in:Low,Medium,High',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $task = OnwerTask::findOrFail($id);
  
    $task->department_id = $request->department_id;
    $task->team_lead_id = $request->team_lead_id;
   

    if ($task->save()) {
        // Check if the task has an associated account and update if necessary
        if ($task->account) {
            $account = $task->account;
            $account->clientname = $request->client_name;
            $account->email = $request->client_email;
            $account->phone = $request->client_contact;
            $account->due_date = $request->deadline;
            $account->nature_of_business = $request->nature_of_business;
            $account->priority = $request->priority;

            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'public_id' => 'manager_task/' . uniqid() . '_' . $file->getClientOriginalName(),
                    'resource_type' => 'auto',
                ]);
                $account->attachments = $uploaded['secure_url'];
            }

            $account->save();
        }

        // Notify all team leads of the department
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

    function getCloudinaryPublicId($url)
    {
        $parts = explode('/', $url);
        $uploadIndex = array_search('upload', $parts);
        if ($uploadIndex !== false && isset($parts[$uploadIndex + 2])) {
            $folderParts = array_slice($parts, $uploadIndex + 2);
            return implode('/', array_map(function ($part) {
                return pathinfo($part, PATHINFO_FILENAME);
            }, $folderParts));
        }
        return null;
    }

    function subtask()
    {
        $manager = Auth::guard('project_manager')->user();

        // If it's already an array, use it directly
        $departmentIds = $manager->department_ids;

        // Make sure it's a valid array of integers
        if (!is_array($departmentIds) || empty($departmentIds)) {
            $departmentIds = [];
        } else {
            $departmentIds = array_map('intval', $departmentIds);
        }

        // Now get subtasks for those departments
        $subtasks = Subtask::whereIn('department_id', $departmentIds)
            ->with('employee')
            ->get();

        return view('project_manager.subtask', compact('subtasks'));
    }

     function subtask_detail($id)
    {
        $subtask = Subtask::with(['employee.department', 'employeeSubtask'])->findOrFail($id);

        $employeeId = $subtask->assigned_employee_id;

        $employeeSubtasks = Subtask::with('employeeSubtask')
            ->where('assigned_employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();

        $attachments = [];

        if ($subtask->employeeSubtask && $subtask->employeeSubtask->attachments) {
            $attachments = is_array($subtask->employeeSubtask->attachments)
                ? $subtask->employeeSubtask->attachments
                : json_decode($subtask->employeeSubtask->attachments, true);

            if (!is_array($attachments)) {
                $attachments = explode(',', $subtask->employeeSubtask->attachments);
            }

            $attachments = collect($attachments)->flatten()->filter(function ($url) {
                return is_string($url) && !empty(trim($url));
            })->values()->all();
        }


        return view('project_manager.subtask_detail', compact('subtask', 'employeeSubtasks', 'attachments'));
    }
}
