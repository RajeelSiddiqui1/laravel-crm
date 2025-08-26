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

        $subtasks = Subtask::with('task')
            ->where('assigned_employee_id', $employee->id)
            ->get();

        return view('employee.subtasks_list', compact('subtasks'));
    }



   public function employee_task_view($subtaskId)
    {
        // Fetch the subtask with its employeeSubtask relationship
        $subtask = Subtask::with(['employeeSubtask', 'task'])->findOrFail($subtaskId);

        // Check if employeeSubtask exists
        if (!$subtask->employeeSubtask) {
            return redirect()->back()->with('error_swal', 'No employee subtask found.');
        }

        $employeeSubtask = $subtask->employeeSubtask;

        // Determine lead count (default to 1 if not set)
        $leadCount = (int) ($subtask->lead ?? 1);
        $leadValues = range(1, $leadCount);

        // Check task type
        $isCallCenterPos = $subtask->task_type === 'cell_center_pos';
        $isCallCenterAccount = $subtask->task_type === 'cell_center_accounts';

        // Fetch CellCenterPos or CellCenterAccount records based on JSON IDs
        $posData = [];
        $accountData = [];
        if ($isCallCenterPos && !empty($employeeSubtask->cell_center_pos_ids)) {
            $posData = CellCenterPos::whereIn('id', $employeeSubtask->cell_center_pos_ids)->get()->keyBy('id');
        } elseif ($isCallCenterAccount && !empty($employeeSubtask->cell_center_account_ids)) {
            $accountData = CellCenterAccount::whereIn('id', $employeeSubtask->cell_center_account_ids)->get()->keyBy('id');
        }

        return view('employee.subtasks_update', compact('subtask', 'employeeSubtask', 'leadValues', 'isCallCenterPos', 'isCallCenterAccount', 'posData', 'accountData'));
    }

public function updatePos(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'lead' => 'required|integer',
            'status' => 'required|in:pending,in_progress,completed',
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
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg|max:10240', // 10MB max per file
        ]);

        // Find the subtask
        $subtask = Subtask::findOrFail($id);

        // Get the authenticated employee's ID
        $employeeId = Auth::guard('employee')->user()->id;

        // Get the lead index
        $lead = $request->input('lead');
        $posId = $subtask->cell_center_pos_ids[$lead - 1] ?? null;

        // Find or create the CellCenterPos record
        if ($posId) {
            $posData = CellCenterPos::findOrFail($posId);
        } else {
            $posData = new CellCenterPos();
            $posData->employee_id = $employeeId;
        }

        // Update fields
        $posData->status = $request->input('status');
        $posData->comment = $request->input('comment');
        $posData->name = $request->input('name');
        $posData->business_name = $request->input('business_name');
        $posData->business_number = $request->input('business_number');
        $posData->personal_number = $request->input('personal_number');
        $posData->personal_email = $request->input('personal_email');
        $posData->business_email = $request->input('business_email');
        $posData->address = $request->input('address');
        $posData->provider = $request->input('provider');
        $posData->category_pos = $request->input('category_pos');
        $posData->pos_type = $request->input('pos_type');
        $posData->debt = $request->input('debt');
        $posData->credit = $request->input('credit');
        $posData->rental = $request->input('rental');
        $posData->business_type = $request->input('business_type');
        $posData->date = $request->input('date');
        $posData->time = $request->input('time');

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $uploadedFiles = $posData->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'subtask_attachments',
                    'resource_type' => in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : (in_array(strtolower($file->getClientOriginalExtension()), ['mp3', 'wav', 'ogg']) ? 'raw' : 'image'),
                ]);
                $uploadedFiles[] = $uploadedFile->getSecurePath();
            }
            $posData->attachments = $uploadedFiles;
        }

        // Save the record
        $posData->save();

        // Update the subtask's cell_center_pos_ids
        if (!$posId) {
            $subtask->cell_center_pos_ids = array_merge($subtask->cell_center_pos_ids ?? [], [$posData->id]);
            $subtask->save();
        }

        return redirect()->back()->with('success_swal', 'POS data updated successfully.');
    }

    public function updateAccount(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'lead' => 'required|integer',
            'status' => 'required|in:pending,in_progress,completed',
            'comments' => 'nullable|string',
            'driving_license' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'business_number' => 'nullable|string|max:255',
            'corporation_number' => 'nullable|string|max:255',
            'corporation_email' => 'nullable|email|max:255',
            'corporation_documents' => 'nullable|string',
            'previous_history' => 'nullable|string',
            'fees' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg|max:10240', // 10MB max per file
        ]);

        // Find the subtask
        $subtask = Subtask::findOrFail($id);

        // Get the authenticated employee's ID
        $employeeId = Auth::guard('employee')->user()->id;

        // Get the lead index
        $lead = $request->input('lead');
        $accountId = $subtask->cell_center_account_ids[$lead - 1] ?? null;

        // Find or create the CellCenterAccount record
        if ($accountId) {
            $accountData = CellCenterAccount::findOrFail($accountId);
        } else {
            $accountData = new CellCenterAccount();
            $accountData->subtask_id = $subtask->id;
            $accountData->employee_id = $employeeId;
        }

        // Update fields
        $accountData->status = $request->input('status');
        $accountData->comments = $request->input('comments');
        $accountData->driving_license = $request->input('driving_license');
        $accountData->email = $request->input('email');
        $accountData->phone = $request->input('phone');
        $accountData->business_number = $request->input('business_number');
        $accountData->corporation_number = $request->input('corporation_number');
        $accountData->corporation_email = $request->input('corporation_email');
        $accountData->corporation_documents = $request->input('corporation_documents');
        $accountData->previous_history = $request->input('previous_history');
        $accountData->fees = $request->input('fees');

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $uploadedFiles = $accountData->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'subtask_attachments',
                    'resource_type' => in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : (in_array(strtolower($file->getClientOriginalExtension()), ['mp3', 'wav', 'ogg']) ? 'raw' : 'image'),
                ]);
                $uploadedFiles[] = $uploadedFile->getSecurePath();
            }
            $accountData->attachments = $uploadedFiles;
        }

        // Save the record
        $accountData->save();

        // Update the subtask's cell_center_account_ids
        if (!$accountId) {
            $subtask->cell_center_account_ids = array_merge($subtask->cell_center_account_ids ?? [], [$accountData->id]);
            $subtask->save();
        }

        return redirect()->back()->with('success_swal', 'Account data updated successfully.');
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
