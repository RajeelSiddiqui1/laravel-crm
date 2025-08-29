<?php

namespace App\Http\Controllers;

use App\Mail\AssignedTeamLeaderTask;
use App\Models\AccountHST;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OnwerTask;
use App\Models\ProjectManager as ModelsProjectManager;
use App\Models\ProjectOwner;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\AccountT1;
use App\Models\AccountT2;
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

        $tasks = OnwerTask::select('id', 'client_name', 'audio_url')
            ->whereRaw('JSON_CONTAINS(managers, ?)', [json_encode((string)$manager->id)])
            ->get();

        return view('project_manager.owner_tasks', compact('tasks'));
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

        // Dusre managers dropdown ke liye
        $otherManagers = ModelsProjectManager::where('id', '!=', $currentManager->id)
            ->pluck('name', 'id');

        // Sirf current manager ke tasks/accounts laane ke liye
        $accountst1 = AccountT1::where('project_manager_id', $currentManager->id)->get();
        $accountst2 = AccountT2::where('project_manager_id', $currentManager->id)->get();
        $accountsthst = AccountHST::where('project_manager_id', $currentManager->id)->get();

        return view('project_manager.my_task', compact(
            'accountst1',
            'accountst2',
            'accountsthst',
            'otherManagers'
        ));
    }


public function my_task_detail($id)
{
    // Assuming $manager is available (e.g., via Auth::user() or middleware)
    $manager = Auth::guard('project_manager')->user(); // Adjust based on how $manager is obtained in your application
    $deptIds = $manager->department_ids ?? [];

    if (empty($deptIds)) {
        return redirect()->route('project_manager.mytask')->with('error_swal', 'No departments assigned to you.');
    }

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
        return redirect()->route('project_manager.mytask')->with('error_swal', 'Account not found.');
    }

    return view('project_manager.my_task_detail', compact('account', 'accountType'));
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


    public function create_my_task($id)
    {
        $task = OnwerTask::findOrFail($id);

        $manager = Auth::guard('project_manager')->user();
        $deptIds = $manager->department_ids;

        $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
        $team_leads = TeamLead::whereIn('department_id', $deptIds)->get(['id', 'name', 'department_id']);

        $isAccounts = Department::whereIn('id', $deptIds)->where('name', 'Accounts')->exists();
        $accountsT2 = $isAccounts ? AccountT2::take(1)->get(['id', 'clientname', 'email', 'due_date', 'nature_of_business', 'corporation_name', 'corporation_number', 'attachments', 'priority']) : collect();
        $accountsHST = $isAccounts ? AccountHST::take(1)->get(['id', 'clientname', 'email', 'due_date', 'nature_of_business', 'corporation_name', 'corporation_number', 'attachments', 'priority']) : collect();
        $accountsT1 = $isAccounts ? AccountT1::take(1)->get(['id', 'clientname', 'period', 'driving_license', 'sim_number', 'bussiness_name', 'famliy_name', 'year']) : collect();

        return view('project_manager.create_my_task', compact('task', 'departments', 'team_leads', 'isAccounts', 'accountsT2', 'accountsHST', 'accountsT1'));
    }

public function store_my_task(Request $request, $id)
{
    $manager = Auth::guard('project_manager')->user();

    $isAccounts = Department::whereIn('id', $manager->department_ids)
        ->where('name', 'Accounts')
        ->exists();

    $rules = [
        'department_id' => 'required|exists:departments,id',
        'team_lead_id' => 'required|exists:team_leads,id',
        'account_type' => 'required|in:AccountT1,AccountT2,AccountHST',
    ];

    if ($isAccounts) {
        if ($request->account_type === 'AccountT1') {
            $rules = array_merge($rules, [
                'clientname_t1' => 'required|string|max:255',
                'period_t1' => 'required|string|max:255',
                'driving_license_t1' => 'required|string|max:255',
                'sim_number_t1' => 'required|string|max:255',
                'bussiness_name_t1' => 'required|string|max:255',
                'famliy_name_t1' => 'required|string|max:255',
                'year_t1' => 'required|string|max:255',
            ]);
        } elseif ($request->account_type === 'AccountT2') {
            $rules = array_merge($rules, [
                'clientname_t2' => 'required|string|max:255',
                'email_t2' => 'required|email|max:255',
                'phone_t2' => 'nullable|string|max:20',
                'corporation_name_t2' => 'nullable|string|max:255',
                'corporation_number_t2' => 'nullable|string|max:255',
                'due_date_t2' => 'required|date',
                'nature_of_business_t2' => 'required|string',
                'priority_t2' => 'required|in:low,medium,high',
                'attachments_t2' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,csv,txt,rtf,mp4,avi,mov,webm,mp3,wav,ogg,zip,rar,7z,js,html,css,php,py,java,c,cpp,dart|max:20480',
            ]);
        } elseif ($request->account_type === 'AccountHST') {
            $rules = array_merge($rules, [
                'clientname_hst' => 'required|string|max:255',
                'email_hst' => 'required|email|max:255',
                'phone_hst' => 'nullable|string|max:20',
                'corporation_name_hst' => 'nullable|string|max:255',
                'corporation_number_hst' => 'nullable|string|max:255',
                'due_date_hst' => 'required|date',
                'nature_of_business_hst' => 'required|string',
                'priority_hst' => 'required|in:low,medium,high',
                'attachments_hst' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,csv,txt,rtf,mp4,avi,mov,webm,mp3,wav,ogg,zip,rar,7z,js,html,css,php,py,java,c,cpp,dart|max:20480',
            ]);
        }
    }

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // ✅ First fetch the OwnerTask
    $task = OnwerTask::findOrFail($id);

    if ($request->account_type === 'AccountT1' && $task->account_t1_id) {
    return redirect()->back()->with('error_swal', 'Task already exists with AccountT1');
}

if ($request->account_type === 'AccountT2' && $task->account_t2_id) {
    return redirect()->back()->with('error_swal', 'Task already exists with AccountT2');
}

if ($request->account_type === 'AccountHST' && $task->account_hst_id) {
    return redirect()->back()->with('error_swal', 'Task already exists with AccountHST');
}

    $account = null;

    if ($isAccounts) {
        if ($request->account_type === 'AccountT1') {
            $account = new AccountT1();
            $account->clientname = $request->clientname_t1;
            $account->period = $request->period_t1;
            $account->driving_license = $request->driving_license_t1;
            $account->sim_number = $request->sim_number_t1;
            $account->bussiness_name = $request->bussiness_name_t1;
            $account->famliy_name = $request->famliy_name_t1;
            $account->year = $request->year_t1;
            $account->project_manager_id = $manager->id;
            $account->task_id = $task->id; // ✅ correct
            $account->save();
        } elseif ($request->account_type === 'AccountT2') {
            $account = new AccountT2();

            $attachments = null;
            if ($request->hasFile('attachments_t2')) {
                try {
                    $file = $request->file('attachments_t2');
                    $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                        'public_id' => 'manager_task/' . uniqid() . '_' . $file->getClientOriginalName(),
                        'resource_type' => 'auto',
                    ]);
                    $attachments = $uploaded['secure_url'];
                } catch (\Exception $e) {
                    Log::error('Cloudinary upload failed: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'File upload failed')->withInput();
                }
            }

            $account->clientname = $request->clientname_t2;
            $account->email = $request->email_t2;
            $account->phone = $request->phone_t2;
            $account->corporation_name = $request->corporation_name_t2;
            $account->corporation_number = $request->corporation_number_t2;
            $account->due_date = $request->due_date_t2;
            $account->nature_of_business = $request->nature_of_business_t2;
            $account->priority = $request->priority_t2;
            $account->attachments = $attachments;
            $account->project_manager_id = $manager->id;
            $account->task_id = $task->id; // ✅ correct
            $account->save();
        } elseif ($request->account_type === 'AccountHST') {
            $account = new AccountHST();

            $attachments = null;
            if ($request->hasFile('attachments_hst')) {
                try {
                    $file = $request->file('attachments_hst');
                    $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                        'public_id' => 'manager_task/' . uniqid() . '_' . $file->getClientOriginalName(),
                        'resource_type' => 'auto',
                    ]);
                    $attachments = $uploaded['secure_url'];
                } catch (\Exception $e) {
                    Log::error('Cloudinary upload failed: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'File upload failed')->withInput();
                }
            }

            $account->clientname = $request->clientname_hst;
            $account->email = $request->email_hst;
            $account->phone = $request->phone_hst;
            $account->corporation_name = $request->corporation_name_hst;
            $account->corporation_number = $request->corporation_number_hst;
            $account->due_date = $request->due_date_hst;
            $account->nature_of_business = $request->nature_of_business_hst;
            $account->priority = $request->priority_hst;
            $account->attachments = $attachments;
            $account->project_manager_id = $manager->id;
            $account->task_id = $task->id; // ✅ correct
            $account->save();
        }
    }

    // ✅ Update the OwnerTask
    $task->department_id = $request->department_id;
    $task->team_lead_id = $request->team_lead_id;
    $task->status = 'pending';
    $task->project_manager_id = $manager->id;
    $task->project_manager_task = $manager->id;

    if ($isAccounts && $account) {
        if ($request->account_type === 'AccountT1') {
            $task->account_t1_id = $account->id;
        } elseif ($request->account_type === 'AccountT2') {
            $task->account_t2_id = $account->id;
        } elseif ($request->account_type === 'AccountHST') {
            $task->account_hst_id = $account->id;
        }
    }

    $task->save();

    // Notify Team Leads
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
        $manager = Auth::guard('project_manager')->user();

        $deptIds = $manager->department_ids ?? [];
        if (empty($deptIds)) {
            return redirect()->route('project_manager.mytask')->with('error_swal', 'No departments assigned to you.');
        }

        $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
        $team_leads = TeamLead::whereIn('department_id', $deptIds)->get(['id', 'name', 'department_id']);

        $accountT1  = AccountT1::with('ownerTask')->find($id);
        $accountT2  = AccountT2::with('ownerTask')->find($id);
        $accountHST = AccountHST::with('ownerTask')->find($id);

        $account     = null;
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
            return redirect()->route('project_manager.mytask')->with('error_swal', 'Task not found.');
        }

        return view('project_manager.edit_my_task', compact('account', 'accountType', 'departments', 'team_leads'));
    }


 public function mytask_update(Request $request, $id)
{
    $accountT1 = AccountT1::find($id);
    $accountT2 = AccountT2::find($id);
    $accountHST = AccountHST::find($id);

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
        return redirect()->route('project_manager.mytask')->with('error_swal', 'Task not found.');
    }

    $rules = [];

    if ($accountType === 'T1') {
        $rules = [
            'period' => 'required|string|max:255',
            'driving_license' => 'required|string|max:255',
            'sim_number' => 'required|string|max:255',
            'year' => 'required|string|max:4',
        ];
    } elseif ($accountType === 'T2' || $accountType === 'HST') {
        $rules = [
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'due_date' => 'required|date',
            'corpration_number' => 'nullable|string|max:255',
            'corpration_name' => 'required|string|max:255',
            'nature_of_business' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'attachments' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    if ($accountType === 'T1') {
        $account->period = $request->input('period');
        $account->driving_license = $request->input('driving_license');
        $account->sim_number = $request->input('sim_number');
        $account->bussiness_name = $request->input('business_name');
        $account->famliy_name = $request->input('family_name');
        $account->year = $request->input('year');
    } elseif ($accountType === 'T2' || $accountType === 'HST') {
        $account->phone = $request->input('phone');
        $account->email = $request->input('email');
        $account->due_date = $request->input('due_date');
        $account->corporation_number = $request->input('corpration_number');
        $account->corporation_name = $request->input('corpration_name');
        $account->nature_of_business = $request->input('nature_of_business');
        $account->priority = $request->input('priority');

        if ($request->hasFile('attachments')) {
            if ($account->attachments) {
                $publicId = $this->getCloudinaryPublicId($account->attachments);
                if ($publicId) {
                    Cloudinary::destroy($publicId);
                }
            }
            $uploadedFile = $request->file('attachments');
            $uploadResult = Cloudinary::uploadApi()->upload($uploadedFile->getRealPath(), [
                'folder' => 'attachments',
                'resource_type' => 'auto',
            ]);
            $account->attachments = $uploadResult['secure_url'];
        }
    }

    $account->save();

    foreach (TeamLead::where('department_id', $account->department_id)->get() as $teamLead) {
        Notification::create([
            'title' => 'Task Updated',
            'message' => 'Task has been updated in your department.',
            'user_id' => $teamLead->id,
            'user_type' => 'team_lead',
        ]);
    }

    return redirect()->route('project_manager.mytask')->with('success_swal', 'Task updated and team leads notified.');
}


    /**
     * Extract Cloudinary public ID from URL
     *
     * @param string $url
     * @return string|null
     */
    protected function getCloudinaryPublicId($url)
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


public function my_task_destroy($id)
{
    try {
        // Handle AccountT1
        $taskT1 = AccountT1::find($id);
        if ($taskT1) {
            // Delete attachment from Cloudinary
            if ($taskT1->attachments) {
                $publicId = pathinfo(parse_url($taskT1->attachments, PHP_URL_PATH), PATHINFO_FILENAME);
                Cloudinary::uploadApi()->destroy('manager_task/' . $publicId, ['resource_type' => 'auto']);
            }
            // Set account_t1_id to null in OwnerTask
            OnwerTask::where('account_t1_id', $id)->update(['account_t1_id' => null]);
            // Delete the AccountT1 task
            $taskT1->delete();
            return redirect()->route('project_manager.mytask')
                ->with('success_swal', 'AccountT1 task deleted and foreign key cleared successfully.');
        }

        // Handle AccountT2
        $taskT2 = AccountT2::find($id);
        if ($taskT2) {
            // Delete attachment from Cloudinary
            if ($taskT2->attachments) {
                $publicId = pathinfo(parse_url($taskT2->attachments, PHP_URL_PATH), PATHINFO_FILENAME);
                Cloudinary::uploadApi()->destroy('manager_task/' . $publicId, ['resource_type' => 'auto']);
            }
            // Set account_t2_id to null in OwnerTask
            OnwerTask::where('account_t2_id', $id)->update(['account_t2_id' => null]);
            // Delete the AccountT2 task
            $taskT2->delete();
            return redirect()->route('project_manager.mytask')
                ->with('success_swal', 'AccountT2 task deleted and foreign key cleared successfully.');
        }

        // Handle AccountHST
        $taskHST = AccountHST::find($id);
        if ($taskHST) {
            // Delete attachment from Cloudinary
            if ($taskHST->attachments) {
                $publicId = pathinfo(parse_url($taskHST->attachments, PHP_URL_PATH), PATHINFO_FILENAME);
                Cloudinary::uploadApi()->destroy('manager_task/' . $publicId, ['resource_type' => 'auto']);
            }
            // Set account_hst_id to null in OwnerTask
            OnwerTask::where('account_hst_id', $id)->update(['account_hst_id' => null]);
            // Delete the AccountHST task
            $taskHST->delete();
            return redirect()->route('project_manager.mytask')
                ->with('success_swal', 'AccountHST task deleted and foreign key cleared successfully.');
        }

        // If no task found
        return redirect()->route('project_manager.mytask')
            ->with('error_swal', 'Task not found.');

    } catch (\Exception $e) {
        Log::error('Task deletion failed: ' . $e->getMessage());
        return redirect()->route('project_manager.mytask')
            ->with('error_swal', 'Failed to delete task. Please try again.');
    }

}    function subtask()
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


    public function teamleads()
    {
        $manager = Auth::guard('project_manager')->user();
        $teamleads = $manager->teamleads; 

        return view('project_manager.teamleads', compact('teamleads'));
    }

    public function create_teamlead_view(){
      
        $manager = Auth::guard('project_manager')->user();
        $deptIds = $manager->department_ids;

        $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
        return view('project_manager.create_teamlead', compact('departments'));
    }

    public function create_teamlead(Request $request){
        $manager = Auth::guard('project_manager')->user();
        $deptIds = $manager->department_ids;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:team_leads',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_id' => '    exists:departments,id|in:' . implode(',', $deptIds),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teamLead = new TeamLead();
        $teamLead->name = $request->name;
        $teamLead->email = $request->email;
        $teamLead->phone = $request->phone;
        $teamLead->password = bcrypt($request->password);
        $teamLead->department_id = $request->department_id;
        $teamLead->manager_id = $manager->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/team_leads'), $imageName);
            $teamLead->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/team_leads/$imageName"), $imageContent);
                $teamLead->image = $imageName;
            }
        }

        if ($teamLead->save()) {
            session()->flash('success_swal', 'Team Lead created successfully.');
            return redirect()->route('project_manager.teamleads');
        }

        session()->flash('error_swal', 'Failed to create Team Lead.');
        return redirect()->back()->withInput();
    }
    public function employees()
    {
        $manager = Auth::guard('project_manager')->user();
        $employees = $manager->employees; 

        return view('project_manager.employees', compact('employees'));
    }

    public function create_employee_view(){
      
        $manager = Auth::guard('project_manager')->user();
        $deptIds = $manager->department_ids;

        $departments = Department::whereIn('id', $deptIds)->get(['id', 'name']);
        return view('project_manager.create_employee', compact('departments'));
    }

    public function create_employee(Request $request){
        $manager = Auth::guard('project_manager')->user();
        $deptIds = $manager->department_ids;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:team_leads',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_id' => 'exists:departments,id|in:' . implode(',', $deptIds),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->password = bcrypt($request->password);
        $employee->department_id = $request->department_id;
        $employee->manager_id = $manager->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/employees'), $imageName);
            $employee->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/team_leads/$imageName"), $imageContent);
                $employee->image = $imageName;
            }
        }

        if ($employee->save()) {
            session()->flash('success_swal', 'Team Lead created successfully.');
            return redirect()->route('project_manager.employee');
        }

        session()->flash('error_swal', 'Failed to create Team Lead.');
        return redirect()->back()->withInput();
    }
}
