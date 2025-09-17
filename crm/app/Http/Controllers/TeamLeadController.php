<?php

namespace App\Http\Controllers;

use App\Mail\AssignedEmployeeTask;
use App\Models\AccountHST;
use App\Models\CellCenterAccount;
use App\Models\CellCenterPos;
use App\Models\ManagerOperation;
use App\Models\Message;
use App\Models\OnwerTask;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\AccountT1;
use App\Models\AccountT2;
use App\Models\TeamLead;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\EmployeeSubtask;
use App\Models\Notification;
use App\Models\ProjectManager;
use App\Models\SharedTask;
use Carbon\Carbon;
use SweetAlert2\Laravel\Swal;

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
            session('success_swal', 'Validation failed. Please check your input.');
            return redirect()->back()->witherror_swals($validator)->withInput();
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

            return redirect()->route('welcome')->with('success_swal', 'Team Lead registered success_swalfully. Check your email for login link.');
        }


        return redirect()->back()->withInput()->with('error_swal', 'Failed to register Team Lead.');
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
            session('error_swal', 'Validation failed. Please check your input.');
            return redirect()->back()->witherror_swals($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('team_lead')->attempt($credentials)) {

            return redirect()->route('team_lead.home')->with('success_swal', 'Login successfully!');
        }


        return redirect()->back()->withInput()->with('error_swal', 'Invalid login credentials.');
    }

    function tokenLogin($token)
    {
        $team_lead = TeamLead::where('login_token', $token)->first();

        if (!$team_lead) {
            session('error_swal', 'Invalid or expired login token.');
            return redirect()->route('team_lead.login');
        }

        Auth::guard('team_lead')->login($team_lead);
        $team_lead->login_token = null;
        $team_lead->save();

        return redirect()->route('team_lead.home')->with('success_swal', 'Logged in success_swalfully via token!');
    }

    function logout()
    {
        Auth::guard('team_lead')->logout();
        ('Logged out success_swalfully!');
        return redirect()->route('team_lead.login')->with('success_swal', 'logout successfully');
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

        return back()->with('success_swal', 'Profile updated successfully!');
    }

    // public function manager_tasks()
    // {
    //     $teamLead = Auth::guard('team_lead')->user();
    //     $departmentId = $teamLead->department_id;

    //     $tasks = OnwerTask::with(['teamLead', 'department'])
    //         ->where('team_lead_id', $teamLead->id)
    //         ->get();

    //     return view('team_lead.manager_tasks', compact('tasks', 'employees'));
    // }

    public function manager_tasks()
    {
        $teamLead = Auth::guard('team_lead')->user();

        if (!$teamLead) {
            abort(403, 'Unauthorized access');
        }

        $departmentId = $teamLead->department_id;

        // Dusre Team Leads dropdown ke liye
        $otherTeamLeads = TeamLead::where('id', '!=', $teamLead->id)
            ->pluck('name', 'id');

        // Fetch tasks with pagination
        $accountst1 = AccountT1::where('team_lead_id', $teamLead->id)->paginate(10);
        $accountst2 = AccountT2::where('team_lead_id', $teamLead->id)->paginate(10);
        $accountsthst = AccountHST::where('team_lead_id', $teamLead->id)->paginate(10);
        $manageroperation = ManagerOperation::where('team_lead_id', $teamLead->id)->paginate(10);

        // Employees from same department
        $employees = Employee::where('department_id', $departmentId)->get();

        return view('team_lead.manager_tasks', compact(
            'teamLead',
            'accountst1',
            'accountst2',
            'accountsthst',
            'manageroperation',
            'employees',
            'otherTeamLeads'
        ));
    }
    public function assignEmployees(Request $request, OnwerTask $task)
    {
        $request->validate([
            'employee_id' => 'nullable|array',
            'employee_id.*' => 'exists:employees,id',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        // ✅ Ensure the task belongs to the current team lead
        if ($task->team_lead_id !== $teamLead->id) {
            session('error_swal', 'Unauthorized action. This task is not assigned to you.');
            abort(403, 'Unauthorized action. This task is not assigned to you.');
        }

        // ✅ Get employee IDs
        $newIds = $request->input('employee_id', []);
        $existingIds = $task->employee_id ? explode(',', $task->employee_id) : [];
        $mergedIds = array_unique(array_merge($existingIds, $newIds));

        // ✅ Ensure all employees belong to same department
        foreach ($mergedIds as $employeeId) {
            $employee = Employee::find($employeeId);
            if (!$employee || $employee->department_id !== $teamLead->department_id) {

                return redirect()->back()->with('error_swal', 'You can only assign employees from your own department.');
            }
        }

        // ✅ Save updated employee_id string to task
        $task->employee_id = implode(',', $mergedIds);
        $task->save();

        // ✅ Notify and email each employee
        foreach ($mergedIds as $employeeId) {
            $employee = Employee::find($employeeId);

            if ($employee) {
                // Send email (if email exists)
                if ($employee->email) {
                    try {
                        Mail::to($employee->email)->send(new AssignedEmployeeTask($task));
                    } catch (\Exception $e) {
                        // Optionally log error_swal
                    }
                }

                // Create notification
                Notification::create([
                    'title' => 'New Task Assigned',
                    'message' => 'You have been assigned a new task: "' . $task->name . '".',
                    'user_id' => $employee->id,
                    'user_type' => 'employee',
                ]);
            }
        }

        session('success_swal', 'Employees assigned and notified success_swalfully!');
        return redirect()->back()->with('success_swal', 'Employees assigned success_swalfully!');
    }



    public function updateStatus(Request $request, OnwerTask $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        if ($task->team_lead_id !== $teamLead->id) {
            session('success_swal', 'Unauthorized action. This task is not assigned to you.');
            return redirect()->back()->withInput();
        }

        $task->status = $request->status;
        $task->save();

        $projectManager = ProjectManager::find($task->project_manager_id);

        if ($projectManager) {
            Notification::create([
                'title' => 'Task Status Updated',
                'message' => 'Team Lead "' . $teamLead->name . '" updated the task "' . $task->name . '" to status "' . ucfirst(str_replace('_', ' ', $task->status)) . '".',
                'user_id' => $projectManager->id,
                'user_type' => 'project_manager',
            ]);
        }

        session('success_swal', 'Task status updated success_swalfully!');

        return redirect("/team-lead/manager-tasks");
    }

    function manager_tasks_detail($id)
    {
        // Manager fetch karo (agar zarurat ho Auth guard se)
        $manager = Auth::guard('team_lead')->user();
        $deptIds = $manager->department_id ?? [];

        if (empty($deptIds)) {
            return redirect()->route('team_lead.manager_tasks')
                ->with('error_swal', 'No departments assigned to you.');
        }

        // Account models ke sath owner task fetch karo
        $accountT1 = AccountT1::with('ownerTask')->find($id);
        $accountT2 = AccountT2::with('ownerTask')->find($id);
        $accountHST = AccountHST::with('ownerTask')->find($id);

        $account = null;
        $accountType = null;

        if ($accountT1) {
            $account = $accountT1;
            $accountType = 'T1';
        } elseif ($accountT2) {
            $account = $accountT2;
            $accountType = 'T2';
        } elseif ($accountHST) {
            $account = $accountHST;
            $accountType = 'HST';
        } else {
            return redirect()->route('team_lead.manager_tasks')
                ->with('error_swal', 'Account not found.');
        }

        return view('team_lead.manager_tasks_detail', compact('account', 'accountType'));
    }




    public function subtask_create($id)
    {
        $teamLead = Auth::guard('team_lead')->user();

        $accountT1 = AccountT1::find($id);
        $accountT2 = AccountT2::find($id);
        $accountHST = AccountHST::find($id);
        $accountManager = ManagerOperation::find($id);

        $account = null;
        $accountType = null;
        $task = null;
        $managerId = null; // <--- NEW

        if ($accountT1) {
            $account = $accountT1;
            $accountType = 'T1';
            $task = $accountT1->ownerTask;
            $managerId = $task->project_manager_id ?? null;
        } elseif ($accountT2) {
            $account = $accountT2;
            $accountType = 'T2';
            $task = $accountT2->ownerTask;
            $managerId = $task->project_manager_id ?? null;
        } elseif ($accountHST) {
            $account = $accountHST;
            $accountType = 'HST';
            $task = $accountHST->ownerTask;
            $managerId = $task->project_manager_id ?? null;
        } elseif ($accountManager) {
            $account = $accountManager;
            $accountType = 'MANAGER';
            $task = $accountManager->ownerTask;
            $managerId = $task->project_manager_id ?? null;
        } else {
            return redirect()->route('team_lead.manager_tasks')
                ->with('error_swal', 'Account not found.');
        }

        $assignedEmployees = Employee::whereIn('id', function ($query) {
            $query->select('employee_id')->from('owner_tasks');
        })->get();

        return view('team_lead.create_subtask', compact(
            'task',
            'assignedEmployees',
            'account',
            'accountType',
            'managerId'  // pass manager_id to view
        ));
    }



    public function subtask_store(Request $request)
    {
        $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'attachments'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'assigned_employee_id' => 'required|exists:employees,id',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'start_time'           => 'nullable|date_format:H:i',
            'end_time'             => 'nullable|date_format:H:i',
            'lead'                 => 'required|numeric',
            'task_type'            => 'nullable|string',
            'account_type'         => 'required|string',
            'account_id'           => 'required|numeric',
        ]);

        if ($request->filled('start_date') && $request->filled('end_date') && $request->start_date === $request->end_date) {
            if ($request->filled('start_time') && $request->filled('end_time')) {
                if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                    return back()->with('error_swal_swal', 'End time must be after start time on the same day.');
                }
            }
        }

        $teamLead = Auth::guard('team_lead')->user();

        $subtask = new Subtask();
        $subtask->title         = $request->title;
        $subtask->description   = $request->description;
        $subtask->lead          = $request->lead;
        $subtask->task_type     = $request->task_type;
        $subtask->start_date    = $request->start_date;
        $subtask->end_date      = $request->end_date;
        $subtask->start_time    = $request->start_time;
        $subtask->end_time      = $request->end_time;

        // IDs
        $subtask->team_lead_id  = Auth::guard('team_lead')->id(); // team lead creating
        $subtask->employee_id   = $request->assigned_employee_id;  // employee assigned
        $subtask->manager_id    = $request->manager_id ?? null;    // manager owner of the account/task

        // Account type mapping
        if ($request->account_type === 'T1') {
            $subtask->account_t1_id = $request->account_id;
        } elseif ($request->account_type === 'T2') {
            $subtask->account_t2_id = $request->account_id;
        } elseif ($request->account_type === 'HST') {
            $subtask->account_hst_id = $request->account_id;
        } elseif ($request->account_type === 'MANAGER') {
            $subtask->manager_operation_id = $request->account_id;
        }



        if ($request->hasFile('attachments')) {
            try {
                $file = $request->file('attachments');
                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'public_id'     => 'subtasks/' . uniqid() . '_' . $file->getClientOriginalName(),
                    'resource_type' => 'auto',
                ]);
                $subtask->attachments = $uploaded['secure_url'];
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error_swal', 'Failed to upload attachment')->withInput();
            }
        }

        $subtask->save();

        return redirect()->route('team_lead.subtask.list', $request->account_id)
            ->with('success_swal_swal', 'Subtask created successfully.');
    }


    public function subtask_edit($id)
    {
        $subtask = Subtask::with('employee')->findOrFail($id);

        $teamLead = Auth::guard('team_lead')->user();
        $assignedEmployees = Employee::where('department_id', $teamLead->department_id)->get();

        $subtask->start_date = $subtask->start_date ? Carbon::parse($subtask->start_date)->format('Y-m-d') : null;
        $subtask->end_date   = $subtask->end_date ? Carbon::parse($subtask->end_date)->format('Y-m-d') : null;
        $subtask->start_time = $subtask->start_time ? Carbon::parse($subtask->start_time)->format('H:i') : null;
        $subtask->end_time   = $subtask->end_time ? Carbon::parse($subtask->end_time)->format('H:i') : null;

        return view('team_lead.subtask_edit', compact('subtask', 'assignedEmployees'));
    }


    public function subtask_update(Request $request, $id)
    {
        $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'assigned_employee_id' => 'required|exists:employees,id',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'start_time'           => 'nullable|date_format:H:i',
            'end_time'             => 'nullable|date_format:H:i',
            'lead'                 => 'nullable|numeric',
            'account_type'         => 'required|string',
            'account_id'           => 'required|numeric',
        ]);

        if ($request->filled('start_date') && $request->filled('end_date') && $request->start_date === $request->end_date) {
            if ($request->filled('start_time') && $request->filled('end_time')) {
                if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                    return back()->withErrors(['end_time' => 'End time must be after start time on the same day.']);
                }
            }
        }

        $teamLead = Auth::guard('team_lead')->user();
        $subtask  = Subtask::findOrFail($id);

        $employee = Employee::findOrFail($request->assigned_employee_id);
        if ($employee->department_id !== $teamLead->department_id) {
            return back()->with('error_swal', 'Selected employee is not from your department.');
        }

        $subtask->title       = $request->title;
        $subtask->description = $request->description;
        $subtask->employee_id = $employee->id;
        $subtask->start_date  = $request->start_date ?? null;
        $subtask->end_date    = $request->end_date ?? null;
        $subtask->start_time  = $request->start_time ?? null;
        $subtask->end_time    = $request->end_time ?? null;
        $subtask->lead        = $request->lead;

        $subtask->account_t1_id = null;
        $subtask->account_t2_id = null;
        $subtask->account_hst_id = null;
        $subtask->manager_operation_id = null;

        if ($request->account_type === 'T1') {
            $subtask->account_t1_id = $request->account_id;
        } elseif ($request->account_type === 'T2') {
            $subtask->account_t2_id = $request->account_id;
        } elseif ($request->account_type === 'HST') {
            $subtask->account_hst_id = $request->account_id;
        } elseif ($request->account_type === 'MANAGER') {
            $subtask->manager_operation_id = $request->account_id;
        }

        $subtask->save();

        return redirect()->route('team_lead.subtask.list', $request->account_id)
            ->with('success_swal', 'Subtask updated successfully.');
    }


    public function subtask_delete($id)
    {
        $teamLead = Auth::guard('team_lead')->user();
        $subtask  = Subtask::findOrFail($id);

        if ($subtask->team_lead_id !== $teamLead->id) {
            return back()->with('error_swal', 'Unauthorized.');
        }

        $accountId = $subtask->account_t1_id ?? $subtask->account_t2_id ?? $subtask->account_hst_id ?? $subtask->manager_operation_id;

        $subtask->delete();

        return redirect()->route('team_lead.subtask.list', $accountId)
            ->with('success_swal_swal', 'Subtask deleted successfully.');
    }


public function subtask_list($id)
{
    $task = null;
    $taskType = null;

    $t1Task = AccountT1::find($id);
    if ($t1Task) {
        $task = $t1Task;
        $taskType = 'account_t1_id';
    }

    if (!$task) {
        $t2Task = AccountT2::find($id);
        if ($t2Task) {
            $task = $t2Task;
            $taskType = 'account_t2_id';
        }
    }

    if (!$task) {
        $hstTask = AccountHST::find($id);
        if ($hstTask) {
            $task = $hstTask;
            $taskType = 'account_hst_id';
        }
    }

    if (!$task) {
        $operationTask = ManagerOperation::find($id);
        if ($operationTask) {
            $task = $operationTask;
            $taskType = 'manager_operation_id';
        }
    }

    if (!$task) {
        return redirect()->route('team_lead.manager_tasks')->with('error_swal', 'Task not found.');
    }

    $teamLeadId = Auth::guard('team_lead')->id();

    $subtasks = Subtask::with('employee')
        ->where($taskType, $id)
        ->where('team_lead_id', $teamLeadId) // <--- Only show subtasks created by this team lead
        ->get();

    return view('team_lead.subtask_list', compact('task', 'subtasks'));
}
    public function subtask_update_status(Request $request, $id)
    {
        $request->validate([
            'teamlead_status' => 'required|in:pending,completed,late,rejected'
        ]);

        $subtask = Subtask::findOrFail($id);

        if (Auth::guard('team_lead')->id() !== $subtask->team_lead_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this subtask.');
        }

        $subtask->teamlead_status = $request->teamlead_status;
        $subtask->save();


        // Notify the assigned employee
        if ($subtask->employee_id) {
            Notification::create([
                'title' => "Subtask Status Updated",
                'user_id' => $subtask->employee_id,
                'user_type' => 'employee',
                'message' => 'Your Subtask #' . $subtask->id . ' has been updated to "' . $request->teamlead_status . '" by Team Lead.',
            ]);
        }

        return redirect()->back()->with('success_swal', 'Subtask status updated and all relevant users notified.');
    }



    public function subtask_detail($id)
    {
        $subtask = Subtask::with(['employee.department', 'employeeSubtask'])->findOrFail($id);

        $employeeId = $subtask->assigned_employee_id;

        // All subtasks assigned to this employee
        $employeeSubtasks = Subtask::with('employeeSubtask')
            ->where('assigned_employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('team_lead.subtask_detail', compact('subtask', 'employeeSubtasks'));
    }



    public function EmployeeSubtasks($subtaskId)
    {
        $subtask = Subtask::findOrFail($subtaskId);

        // Get POS records
        $posRecords = $subtask->call_center_pos_ids
            ? CellCenterPos::whereIn('id', $subtask->call_center_pos_ids)->get()
            : collect();

        // Get Account records
        $accountRecords = $subtask->cell_center_account_ids
            ? CellCenterAccount::whereIn('id', $subtask->cell_center_account_ids)->get()
            : collect();

        return view('team_lead.employee_subtasks', compact('subtask', 'posRecords', 'accountRecords'));
    }

    public function subtask_show_more($id)
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


        return view('team_lead.subtask_show_more', compact('subtask', 'employeeSubtasks', 'attachments'));
    }



    public function showSharedTasks()
    {
        // TeamLead login
        $teamlead = Auth::guard('team_lead')->user();

        // Har teamlead ka single department_id hota hai
        $departmentId = $teamlead->department_id;

        // Employees fetch karo jo is teamlead ke department me hain
        $employees = Employee::where('department_id', $departmentId)->get();

        // Shared tasks jo iss teamlead ko assign hue hain
        $sharedTasks = SharedTask::where('assigned_teamlead_id', $teamlead->id)->get();

        $posResults = [];
        $accountResults = [];

        foreach ($sharedTasks as $shared) {
            if ($shared->cell_center_pos_id) {
                $pos = CellCenterPos::find($shared->cell_center_pos_id);
                if ($pos) {
                    $pos->shared_task_id = $shared->id;
                    $pos->shared_status = $shared->status;
                    $pos->assigned_employee_id = $shared->assigned_employee_id;
                    $posResults[] = $pos;
                }
            } elseif ($shared->cell_center_account_id) {
                $account = CellCenterAccount::find($shared->cell_center_account_id);
                if ($account) {
                    $account->shared_task_id = $shared->id;
                    $account->shared_status = $shared->status;
                    $account->assigned_employee_id = $shared->assigned_employee_id;
                    $accountResults[] = $account;
                }
            }
        }

        return view(
            'team_lead.shared_task_list',
            compact('sharedTasks', 'posResults', 'accountResults', 'employees')
        );
    }

    /**
     * Assign an employee to shared task
     */
    public function assign_employee_shared_task(Request $request, $sharedTaskId)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $sharedTask = SharedTask::findOrFail($sharedTaskId);
        $sharedTask->assigned_employee_id = $request->employee_id;
        $sharedTask->save();

        return redirect()->back()->with('success', 'Employee assigned successfully.');
    }


    public function showPos($id)
    {
        $pos = CellCenterPos::findOrFail($id);
        return view('team_lead.pos_detail', compact('pos'));
    }

    // Account Detail
    public function showAccount($id)
    {
        $account = CellCenterAccount::findOrFail($id);
        return view('team_lead.account_detail', compact('account'));
    }


    function fetch_employee()
    {
        $teamLead   =  Auth::guard('team_lead')->user();
        $employees = Employee::where('department_id', $teamLead->department_id)
            ->with('department')
            ->get();

        return view('team_lead.employees', compact('employees'));
    }






    public function send_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string',
            'receiver_id' => 'required|integer',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,mp3,wav,ogg,pdf,doc,docx,xls,xlsx,txt|max:102400',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_swal', $validator->error_swals()->first());
        }

        $message = new Message();
        $message->content = $request->content;
        $message->receiver_id = $request->receiver_id;

        if (Auth::guard('team_lead')->check()) {
            $message->sender_id = Auth::guard('team_lead')->id();
        } elseif (Auth::guard('employee')->check()) {
            $message->sender_id = Auth::guard('employee')->id();
        } else {
            return redirect()->back()->with('error_swal', 'Unauthorized sender');
        }

        // Handle single file upload
        if ($request->hasFile('attachments')) {
            try {
                $file = $request->file('attachments');
                $publicId = 'chat_attachmentss/' . uniqid() . '_' . $file->getClientOriginalName();

                $uploaded = Cloudinary::uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'public_id' => $publicId,
                        'resource_type' => 'auto',
                        'overwrite' => false
                    ]
                );

                $message->attachments = $uploaded['secure_url'];
            } catch (\Exception $e) {
                Log::error_swal('attachments upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error_swal', 'attachments upload failed.');
            }
        }

        if ($message->save()) {
            return redirect()->back()->with('success_swal', 'Message sent success_swalfully.');
        }

        return redirect()->back()->with('error_swal', 'Message not sent.');
    }

    // Assuming you have a method to fetch messages
    function message_employee($id)
    {
        $employee = Employee::findOrFail($id);
        $teamLeadId = Auth::guard('team_lead')->id();

        // Fetch conversation between this team lead and employee
        $messages = Message::where(function ($query) use ($teamLeadId, $id) {
            $query->where('sender_id', $teamLeadId)->where('receiver_id', $id);
        })->orWhere(function ($query) use ($teamLeadId, $id) {
            $query->where('sender_id', $id)->where('receiver_id', $teamLeadId);
        })->orderBy('created_at')->get();

        return view('team_lead.message_employees', compact('employee', 'messages'));
    }
}
