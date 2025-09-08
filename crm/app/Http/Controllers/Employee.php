<?php

namespace App\Http\Controllers;

use App\Models\CellCenterAccount;
use App\Models\CellCenterPos;
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
        return view('employee.teamlead_task_detail', compact('task','accountType', 'account'));
    }

    function subtasks_list()
    {
        $employee = Auth::guard('employee')->user();

        $subtasks = Subtask::where('employee_id', $employee->id)
            ->get();

        return view('employee.subtasks_list', compact('subtasks'));
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

    $isCallCenterPos = $subtask->task_type === 'call_center_pos';
    $isCallCenterAccount = $subtask->task_type === 'cell_center_accounts';

    // Initialize data structures for lead-specific records
    $posRecords = [];
    $accountRecords = [];

    // For POS type tasks
    if ($isCallCenterPos && $subtask->call_center_pos_ids) {
        $posIds = $subtask->call_center_pos_ids; // Assuming cast as array
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

    return view('employee.subtasks_update', compact(
        'subtask', 
        'leadValues', 
        'isCallCenterPos', 
        'isCallCenterAccount',
        'posRecords',
        'accountRecords'
    ));
}
// In your controller, you'll need a unified update method that handles both POS and Account updates.
// Replace the separate updatePos and updateAccount methods with this:

public function updateSubtask(Request $request, $id)
{
    $subtask = Subtask::findOrFail($id);
    $taskType = $subtask->task_type;

    $isPos = $taskType === 'call_center_pos';
    $isAccount = $taskType === 'cell_center_accounts';

    if (!$isPos && !$isAccount) {
        return redirect()->back()->with('error_swal', 'Invalid task type.');
    }

    $rules = [
        'lead' => 'required|integer',
        'status' => 'required|in:pending,in_progress,completed,rejected',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg|max:10240',
    ];

    if ($isPos) {
        $rules = array_merge($rules, [
            'comment' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_number' => 'nullable|string|max:255',
            'personal_number' => 'nullable|string|max:255',
            'personal_email' => 'nullable|email|max:255',
            'business_email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'provider' => 'nullable|string|max:255',
            'category_pos' => 'nullable|string|max:255',
            'pos_type' => 'nullable|string|max:255',
            'debt' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'rental' => 'nullable|numeric',
            'business_type' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
        ]);
    } else if ($isAccount) {
        $rules = array_merge($rules, [
            'comments' => 'nullable|string',
            'driving_license' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'bussiness_number' => 'nullable|string|max:255',
            'corpuration_number' => 'nullable|string|max:255',
            'corpuration_email' => 'nullable|email|max:255',
            'pervious_history' => 'nullable|string',
            'fees' => 'nullable|numeric',
            'corpuration_documents' => 'nullable|string',
        ]);
    }

    $request->validate($rules);

    $employeeId = Auth::guard('employee')->user()->id;
    $lead = $request->input('lead');

    $modelClass = $isPos ? CellCenterPos::class : CellCenterAccount::class;
    $jsonColumn = $isPos ? 'call_center_pos_ids' : 'cell_center_account_ids';
    $recordId = $subtask->$jsonColumn[$lead - 1] ?? null;
    $commentField = $isPos ? 'comment' : 'comments';

    if ($recordId) {
        $record = $modelClass::findOrFail($recordId);
    } else {
        $record = new $modelClass();
        $record->employee_id = $employeeId;
        if ($isAccount) {
            $record->subtask_id = $subtask->id;
        }
    }

    $record->status = $request->input('status');
    $record->$commentField = $request->input($commentField);

    if ($isPos) {
        $posFields = ['name','business_name','business_number','personal_number','personal_email',
                      'business_email','address','provider','category_pos','pos_type',
                      'debt','credit','rental','business_type','date','time'];
        foreach ($posFields as $field) {
            $record->$field = $request->input($field);
        }
    } else if ($isAccount) {
        $accountFields = ['driving_license','email','phone','bussiness_number','corpuration_number',
                          'corpuration_email','pervious_history','fees'];
        foreach ($accountFields as $field) {
            $record->$field = $request->input($field);
        }
        $record->corpuration_documents = $request->input('corpuration_documents');
    }

  if ($request->hasFile('attachments')) {
    $existingAttachments = $record->attachments ?? [];

    // Ensure attachments is an array
    if (!is_array($existingAttachments)) {
        $existingAttachments = json_decode($existingAttachments, true) ?? [];
    }

    foreach ($request->file('attachments') as $file) {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            $resourceType = 'auto';

            // Detect type
            if (in_array($extension, ['mp4', 'mov', 'avi', 'webm'])) {
                $resourceType = 'video';
            } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                $resourceType = 'video'; // Cloudinary treats audio as video
            }

            // Upload to Cloudinary
            $uploadedFile = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'subtask_attachments',
                'public_id' => uniqid() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'resource_type' => $resourceType,
            ]);

            // Save secure URL
            $existingAttachments[] = $uploadedFile['secure_url'];

        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error_swal', 'Failed to upload one or more attachments')->withInput();
        }
    }

    // Save back to record (JSON format is safest)
    $record->attachments = json_encode($existingAttachments);
    $record->save();
}


    $record->save();

    if (!$recordId) {
        $currentIds = $subtask->$jsonColumn ?? [];
        $currentIds[$lead - 1] = $record->id;
        $subtask->$jsonColumn = $currentIds;
        $subtask->save();
    }

    $successMessage = $isPos ? 'POS data updated successfully.' : 'Account data updated successfully.';
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
