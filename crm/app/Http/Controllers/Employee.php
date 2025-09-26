<?php

namespace App\Http\Controllers;

use App\Models\CellCenterAccount;
use App\Models\CellCenterPos;
use App\Models\ClientDetail;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\Employee as ModelsEmployee;
use App\Models\Department;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\OnwerTask;
use App\Models\TeamLead;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeSubtask;
use App\Models\Notification;
use App\Models\SharedTask;

class Employee extends Controller
{
    function resgisterview()
    {
        $departments = Department::all();
        return view('employee.register', compact('departments'));
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->witherror_swals($validator)->withInput();
        }

        $manager = new ModelsEmployee();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        $manager->password = bcrypt($request->password);
        $manager->department_id = $request->department_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/employees'), $imageName);
            $manager->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/employees/$imageName"), $imageContent);
                $manager->image = $imageName;
            }
        }

        if ($manager->save()) {
            $token = Str::random(64);
            $manager->login_token = $token;
            $manager->save();

            $loginLink = route('employee.token.login', ['token' => $token]);
            Mail::to($manager->email)->send(new AuthMail($manager, $loginLink));

            session()->flash('success_swal', 'Team Lead registered successfully.');
            return redirect()->route('welcome');
        }

        session()->flash('error_swal', 'Failed to register Team Lead.');
        return redirect()->back()->withInput();
    }

    function loginview()
    {
        return view('employee.login');
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

        if (Auth::guard('employee')->attempt($credentials)) {
            return redirect()->route('employee.home')->with('success_swal', 'Login successfully');
        }

        return redirect()->back()->with('error_swal', 'Invalid login credentials');
    }

    function tokenLogin($token)
    {
        $employee = ModelsEmployee::where('login_token', $token)->first();

        if (!$employee) {
            return redirect()->route('employee.login')->with('error_swal', 'Invalid or expired login token.');
        }

        Auth::guard('employee')->login($employee);
        $employee->login_token = null;
        $employee->save();

        return redirect()->route('employee.home')->with('success_swal', 'Logged in successfully via token.');
    }

    function logout()
    {
        Auth::guard('employee')->logout();
        return redirect()->route('employee.login')->with('success_swal', 'Logged out successfully');
    }

    function home()
    {
        return view('employee.home');
    }

    function profile_view()
    {
        $employee = Auth::guard('employee')->user();
        return view('employee.profile', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Employee $employee */
        $employee = Auth::guard('employee')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;


        if ($request->hasFile('image')) {
            $oldImage = public_path('images/employees/' . $employee->image);

            if ($employee->image && file_exists($oldImage)) {
                @unlink($oldImage);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/employees'), $imageName);
            $employee->image = $imageName;
        }

        $employee->save();

        return back()->with('success_swal', 'Profile updated successfully!');
    }

    public function team_task_view()
    {
        $employee = Auth::guard('employee')->user();
        $departmentId = $employee->department_id;

        // Fetch tasks with required relations for this employee, only for Accounts department, excluding Call Center
        $tasks = OnwerTask::with(['department', 'teamLead', 'accountT1', 'accountT2', 'accountHst', 'projectManager'])
            ->where('employee_id', $employee->id)
            ->where('department_id', $departmentId)
            ->whereHas('department', function ($query) {
                $query->where('name', '=', 'Accounts')
                    ->where('name', '!=', 'Call Center');
            })
            ->get()
            ->map(function ($task) {
                // Rename "Call Center" to "Call Operator" if needed
                if ($task->department && $task->department->name === 'Call Center') {
                    $task->department->name = 'Call operator';
                }
                return $task;
            });

        return view('employee.teamlead_task', compact('tasks'));
    }

    function teamlead_task_detail($id)
    {
        $task = OnwerTask::with(['department', 'teamLead', 'accountT1', 'accountT2', 'accountHST'])->findOrFail($id);

        // Determine account type
        $accountType = null;
        $account = null;
        if ($task->account_t1_id) {
            $accountType = 'AccountT1';
            $account = $task->accountT1;
        } elseif ($task->account_t2_id) {
            $accountType = 'AccountT2';
            $account = $task->accountT2;
        } elseif ($task->account_hst_id) {
            $accountType = 'AccountHST';
            $account = $task->accountHST;
        }
        return view('employee.teamlead_task_detail', compact('task', 'accountType', 'account'));
    }

    function subtasks_list()
    {
        $employee = Auth::guard('employee')->user();

        $subtasks = Subtask::where('employee_id', $employee->id)
            ->get();

        return view('employee.subtasks_list', compact('subtasks'));
    }



    public function subtask_status_update(Request $request, $id)
    {
        $request->validate([
            'employee_status' => 'required|in:pending,completed,late,reject'
        ]);

        $subtask = Subtask::findOrFail($id);

        // Ensure the authenticated user is the employee assigned to this subtask
        if (Auth::guard('employee')->id() !== $subtask->employee_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this subtask.');
        }

        $subtask->employee_status = $request->employee_status;
        $subtask->save();

        return redirect()->back()->with('success', 'Subtask status updated successfully.');
    }


   public function employee_task_view($subtaskId)
{
    // Fetch the subtask
    $subtask = Subtask::findOrFail($subtaskId);

    // Verify the subtask belongs to the authenticated employee
    if ($subtask->employee_id != Auth::guard('employee')->id()) {
        return redirect()->route('employee.subtasks.list')
            ->with('error_swal', 'You are not authorized to view this subtask.');
    }

    $leadCount = (int) ($subtask->lead ?? 1);
    $leadValues = range(1, $leadCount);

    $isCallCenterPos     = $subtask->task_type === 'call_center_pos';
    $isCallCenterAccount = $subtask->task_type === 'cell_center_accounts';
    $isClientDetails     = $subtask->task_type === 'client_details';

    // Initialize data structures for lead-specific records
    $posRecords          = [];
    $accountRecords      = [];
    $clientDetailRecords = []; // Changed from clientRecords to clientDetailRecords

    // For POS type tasks
    if ($isCallCenterPos && $subtask->call_center_pos_ids) {
        $posIds = $subtask->call_center_pos_ids; // Assuming cast as array in model
        foreach ($posIds as $leadIndex => $posId) {
            if ($posId) {
                $posRecords[$leadIndex + 1] = CellCenterPos::find($posId);
            }
        }
    }

    // For Account type tasks
    if ($isCallCenterAccount && $subtask->cell_center_account_ids) {
        $accountIds = $subtask->cell_center_account_ids; // Assuming cast as array
        foreach ($accountIds as $leadIndex => $accountId) {
            if ($accountId) {
                $accountRecords[$leadIndex + 1] = CellCenterAccount::find($accountId);
            }
        }
    }

    // For Client Details type tasks
    if ($isClientDetails && $subtask->client_detail_ids) {
        $clientIds = $subtask->client_detail_ids; // Assuming cast as array
        foreach ($clientIds as $leadIndex => $clientId) {
            if ($clientId) {
                $clientDetailRecords[$leadIndex + 1] = ClientDetail::find($clientId);
            }
        }
    }

    return view('employee.subtasks_update', compact(
        'subtask',
        'leadValues',
        'isCallCenterPos',
        'isCallCenterAccount',
        'isClientDetails',
        'posRecords',
        'accountRecords',
        'clientDetailRecords' // Changed from clientRecords to clientDetailRecords
    ));
}

public function updateSubtask(Request $request, $id)
{
    $subtask = Subtask::findOrFail($id);
    $taskType = $subtask->task_type;

    $isPos = $taskType === 'call_center_pos';
    $isAccount = $taskType === 'cell_center_accounts';
    $isClientDetails = $taskType === 'client_details';

    if (!$isPos && !$isAccount && !$isClientDetails) {
        return redirect()->back()->with('error_swal', 'Invalid task type.');
    }

    // 🔹 Common validation
    $rules = [
        'lead'   => 'required|integer',
        'status' => 'required|in:pending,in_progress,completed,rejected',
    ];

    // 🔹 Attachment validation (for all)
    if ($isPos || $isAccount || $isClientDetails) {
        $rules['attachments.*'] = 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg|max:10240';
    }

    // 🔹 Task-specific validation
    if ($isPos) {
        $rules = array_merge($rules, [
            'comment'         => 'nullable|string',
            'name'            => 'nullable|string|max:255',
            'business_name'   => 'nullable|string|max:255',
            'business_number' => 'nullable|string|max:255',
            'personal_number' => 'nullable|string|max:255',
            'personal_email'  => 'nullable|email|max:255',
            'business_email'  => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'provider'        => 'nullable|string|max:255',
            'category_pos'    => 'nullable|string|max:255',
            'pos_type'        => 'nullable|string|max:255',
            'debt'            => 'nullable|numeric',
            'credit'          => 'nullable|numeric',
            'rental'          => 'nullable|numeric',
            'business_type'   => 'nullable|string|max:255',
            'date'            => 'nullable|date',
            'time'            => 'nullable|date_format:H:i',
        ]);
    } elseif ($isAccount) {
        $rules = array_merge($rules, [
            'comments'           => 'nullable|string',
            'driving_license'    => 'nullable|string|max:255',
            'email'              => 'nullable|email|max:255',
            'phone'              => 'nullable|string|max:255',
            'business_number'    => 'nullable|string|max:255',
            'corporation_number' => 'nullable|string|max:255',
            'corporation_email'  => 'nullable|email|max:255',
            'previous_history'   => 'nullable|string',
            'fees'               => 'nullable|numeric',
            'corporation_documents' => 'nullable|string',
        ]);
    } elseif ($isClientDetails) {
        $rules = array_merge($rules, [
                  'employee_id' => 'nullable|exists:employees,id',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'date_of_birth' => 'nullable|date',
            'sin' => 'nullable|string',
            'address' => 'nullable|string',
            'mailing_address' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'status_in_canada' => 'nullable|string',
            'ids_driving_passport' => 'nullable|string',
            'ids_expiry_date' => 'nullable|date',
            'education' => 'nullable|string',

            // Corporation
            'corporation_registered_name' => 'nullable|string',
            'fiscal_year_t2' => 'nullable|string',
            'ontario_corporation_no' => 'nullable|string',
            'fiscal_year_hst' => 'nullable|string',
            'business_no' => 'nullable|string',
            'business_activities' => 'nullable|string',
            'date_of_corporation' => 'nullable|date',
            'corporation_key' => 'nullable|string',
            'register_in_cra_for' => 'nullable|string',
            'business_address' => 'nullable|string',
            'corporation_website' => 'nullable|string',
            'corporation_type' => 'nullable|string',
            'ontario_business_corporation_partnership' => 'nullable|string',

            // Financial Institutions & Payments
            'financial_institutions' => 'nullable|string',
            'account_no_void_cheque' => 'nullable|string',
            'credit_card_nos_from' => 'nullable|string',
            'outstanding_balance' => 'nullable|string',

            // Loans & Mortgage
            'loans_from_institutions' => 'nullable|string',
            'loan_outstanding_balance_installment' => 'nullable|string',
            'mortgage_from' => 'nullable|string',
            'mortgage_outstanding_balance_installment' => 'nullable|string',

            // Automotive
            'auto_make_year' => 'nullable|string',
            'lease_or_loan' => 'nullable|string',

            // WSIB
            'wsib_account_no' => 'nullable|string',

            // Other
            'client_introduced_by' => 'nullable|string',
            'category' => 'nullable|string',
            'lmia_work_permit_from' => 'nullable|string',

            // Service charges / fees (must be numeric!)
            'service_charges_fees' => 'nullable|numeric',
            'bookkeeping' => 'nullable|numeric',
            'corporation_tax' => 'nullable|numeric',
            'hst' => 'nullable|numeric',
            'financials' => 'nullable|numeric',
            'personal_tax' => 'nullable|numeric',
            'immigration' => 'nullable|numeric',
            'corporation_registration' => 'nullable|numeric',
            'accounting' => 'nullable|numeric',

            // Signatures
            'mh_enterprises_signature' => 'nullable|string',
            'client_signature' => 'nullable|string',

        ]);
    }

    $request->validate($rules);

    $employeeId = Auth::guard('employee')->id();
    $lead = $request->input('lead');

    // 🔹 Choose model + JSON column + comment field
    $modelClass = $isPos ? CellCenterPos::class : ($isAccount ? CellCenterAccount::class : ClientDetail::class);
    $jsonColumn = $isPos ? 'call_center_pos_ids' : ($isAccount ? 'cell_center_account_ids' : 'client_detail_ids');
    $commentField = $isPos ? 'comment' : ($isAccount ? 'comments' : 'comments');

    // 🔹 Decode JSON column to array
    $currentIds = $subtask->$jsonColumn ?? [];
    $recordId = $currentIds[$lead - 1] ?? null;

    if ($recordId) {
        $record = $modelClass::findOrFail($recordId);
    } else {
        $record = new $modelClass();
        $record->employee_id = $employeeId;
    }

    // 🔹 Assign common fields
    $record->status = $request->input('status');
    if ($request->has($commentField)) {
        $record->$commentField = $request->input($commentField);
    }

    // 🔹 Task-specific assignments
    if ($isPos) {
        foreach ([
            'name', 'business_name', 'business_number', 'personal_number', 'personal_email','comment',
            'business_email', 'address', 'provider', 'category_pos', 'pos_type',
            'debt', 'credit', 'rental', 'business_type', 'date', 'time'
        ] as $field) {
            $record->$field = $request->input($field);
        }
    } elseif ($isAccount) {
        foreach ([
            'driving_license', 'email', 'phone', 'business_number', 'corporation_number',
            'corporation_email', 'previous_history', 'fees', 'corporation_documents'
        ] as $field) {
            $record->$field = $request->input($field);
        }
    } elseif ($isClientDetails) {
        foreach ([
            'last_name', 'first_name', 'telephone', 'email', 'date_of_birth', 'sin','comments',
            'address', 'mailing_address', 'marital_status', 'status_in_canada',
            'ids_driving_passport', 'ids_expiry_date', 'education',
            'corporation_registered_name', 'fiscal_year_t2', 'ontario_corporation_no',
            'fiscal_year_hst', 'business_no', 'business_activities', 'date_of_corporation',
            'corporation_key', 'register_in_cra_for', 'business_address',
            'corporation_website', 'corporation_type', 'ontario_business_corporation_partnership',
            'financial_institutions', 'account_no_void_cheque', 'credit_card_nos_from',
            'outstanding_balance', 'loans_from_institutions', 'loan_outstanding_balance_installment',
            'mortgage_from', 'mortgage_outstanding_balance_installment', 'auto_make_year',
            'lease_or_loan', 'wsib_account_no', 'client_introduced_by', 'category',
            'lmia_work_permit_from', 'service_charges_fees', 'bookkeeping',
            'corporation_tax', 'hst', 'financials', 'personal_tax', 'immigration',
            'corporation_registration', 'accounting', 'mh_enterprises_signature', 'client_signature'
        ] as $field) {
            $record->$field = $request->input($field);
        }
    }

    // 🔹 Handle attachments
    if (($isPos || $isAccount || $isClientDetails) && $request->hasFile('attachments')) {
        $existingAttachments = $record->attachments ? json_decode($record->attachments, true) : [];
        foreach ($request->file('attachments') as $file) {
            try {
                $extension = strtolower($file->getClientOriginalExtension());
                $resourceType = in_array($extension, ['mp4', 'mov', 'avi', 'webm', 'mp3', 'wav', 'ogg']) ? 'video' : 'auto';

                $uploadedFile = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'subtask_attachments',
                    'public_id' => uniqid() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'resource_type' => $resourceType,
                ]);

                $existingAttachments[] = $uploadedFile['secure_url'];
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error_swal', 'Failed to upload attachments')->withInput();
            }
        }
        $record->attachments = json_encode($existingAttachments);
    }

    $record->save();

    // 🔹 Save/update JSON array in subtask
    if (!$recordId) {
        $currentIds[$lead - 1] = $record->id;
        $subtask->$jsonColumn = $currentIds;
        $subtask->save();
    }

    $successMessage = $isPos
        ? 'POS task created successfully.'
        : ($isAccount
            ? 'Account task created successfully.'
            : 'Client task created successfully.');

    return redirect()->back()->with('success_swal', $successMessage);
}

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



    public function showSharedTasks()
    {
        // TeamLead login
        $employee = Auth::guard('employee')->user();

        // Har teamlead ka single department_id hota hai


        // Shared tasks jo iss teamlead ko assign hue hain
        $sharedTasks = SharedTask::where('assigned_employee_id', $employee->id)->get();

        $posResults = [];
        $accountResults = [];
        $clientResults = [];

        foreach ($sharedTasks as $shared) {
            if ($shared->cell_center_pos_id) {
                $pos = CellCenterPos::find($shared->cell_center_pos_id);
                if ($pos) {
                    $pos->shared_task_id = $shared->id;
                    $pos->shared_status = $shared->status;
                    $posResults[] = $pos;
                }
            } elseif ($shared->cell_center_account_id) {
                $account = CellCenterAccount::find($shared->cell_center_account_id);
                if ($account) {
                    $account->shared_task_id = $shared->id;
                    $account->shared_status = $shared->status;
                    $accountResults[] = $account;
                }
            } elseif ($shared->client_details_id) {
            $client = ClientDetail::find($shared->client_details_id);
            if ($client) {
               $client->shared_task_id = $shared->id;
                    $client->shared_status = $shared->status;
                    $clientResults[] = $client;
            }
        }
        }

        return view(
            'employee.shared_task_list',
            compact('sharedTasks', 'posResults', 'accountResults','clientResults')
        );
    }



    public function task_info($id)
    {
        // Find the shared task by ID
        $shared_task = SharedTask::findOrFail($id);

        // Determine if it's a POS or Account and fetch the related record
        $record = null;
        $type = null;

        if ($shared_task->cell_center_pos_id) {
            $record = CellCenterPos::find($shared_task->cell_center_pos_id);
            $type = 'pos';
        } elseif ($shared_task->cell_center_account_id) {
            $record = CellCenterAccount::find($shared_task->cell_center_account_id);
            $type = 'account';
        }

        return view('employee.task_info', compact('shared_task', 'record', 'type'));
    }


    public function update_task_info(Request $request, $id)
    {
        $sharedTask = SharedTask::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,signed,not_avaiable,not_intrested,re_shedule', // Updated to match ENUM
            'comment' => 'nullable|string|max:5000',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg,pdf,xls,xlsx,csv,doc,docx|max:10240',
        ]);

        $attachmentUrl = $sharedTask->attachments;

        if ($request->hasFile('attachments')) {
            try {
                $uploadedFile = Cloudinary::uploadApi()->upload(
                    $request->file('attachments')->getRealPath(),
                    [
                        'folder' => 'shared_tasks',
                        'resource_type' => 'auto',
                    ]
                );
                $attachmentUrl = $uploadedFile['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors([
                    'attachments' => 'Failed to upload file: ' . $e->getMessage(),
                ]);
            }
        }

        $sharedTask->update([
            'status' => $request->status,
            'comment' => $request->comment,
            'attachments' => $attachmentUrl,
        ]);

        return redirect()->route('employee.sharedtask.view')
            ->with('success', 'Shared task updated successfully.');
    }




    public function showPos($id)
    {
        $pos = CellCenterPos::findOrFail($id);
        return view('employee.pos_detail', compact('pos'));
    }

    // Account Detail
    public function showAccount($id)
    {
        $account = CellCenterAccount::findOrFail($id);
        return view('employee.account_detail', compact('account'));
    }


     public function showClient($id)
    {
        $client = ClientDetail::findOrFail($id);
        return view('employee.client_details', compact('client'));
    }


    function signed_task()
    {
        $employee = Auth::guard('employee')->user();

        $shared_task = SharedTask::where(function ($query) use ($employee) {
            $query->where('operation_employee_id', $employee->id)
                ->orWhere('employee_id', $employee->id);
        })->get();

        return view("employee.signed_task", compact('shared_task'));
    }



    public function updateVendorStatus(Request $request, $id)
    {
        $request->validate([
            'vendor_status' => 'required|in:pending,approved,not_approved',
        ]);

        $task = SharedTask::findOrFail($id);
        $task->vendor_status = $request->vendor_status;
        $task->save();

        return redirect()->back()->with('success', 'Vendor status updated successfully.');
    }

    public function updateMachineStatus(Request $request, $id)
    {
        $request->validate([
            'machine_status' => 'required|in:pending,deployed,cancelled',
        ]);

        $task = SharedTask::findOrFail($id);
        $task->machine_status = $request->machine_status;
        $task->save();

        return redirect()->back()->with('success', 'Machine status updated successfully.');
    }

    public function fetch_team_leads()
    {
        $employee = Auth::guard('employee')->user();

        $teamLeads = TeamLead::where('department_id', $employee->department_id)
            ->with('department')
            ->get();

        return view('employee.team_leads', compact('employee', 'teamLeads'));
    }
    public function message_teamlead($id)
    {
        $teamlead = TeamLead::findOrFail($id);
        $employeeId = Auth::guard('employee')->id();

        $messages = Message::where(function ($query) use ($employeeId, $id) {
            $query->where('sender_id', $employeeId)->where('receiver_id', $id);
        })->orWhere(function ($query) use ($employeeId, $id) {
            $query->where('sender_id', $id)->where('receiver_id', $employeeId);
        })->orderBy('created_at')->get();

        return view('employee.teamlead_message', compact('teamlead', 'messages'));
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
                $publicId = 'chat_attachments/' . uniqid() . '_' . $file->getClientOriginalName();

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
            return redirect()->back()->with('success_swal', 'Message sent successfully.');
        }

        return redirect()->back()->with('error_swal', 'Message not sent.');
    }
}
