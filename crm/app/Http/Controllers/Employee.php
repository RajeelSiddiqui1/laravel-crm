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

    function team_task_view()
    {
        $employee = Auth::guard('employee')->user();

        $tasks = OnwerTask::with(['employee', 'department', 'account']) // include account
            ->where('employee_id', $employee->id)
            ->get();

        return view('employee.teamlead_task', compact('tasks'));
    }

    function teamlead_task_detail($id)
    {
        $task = OnwerTask::findOrFail($id);
        return view('employee.teamlead_task_detail', compact('task'));
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
    $subtask = Subtask::with(['employeeSubtask', 'task'])->findOrFail($subtaskId);
    
    // Check if employeeSubtask exists
    if (!$subtask->employeeSubtask) {
        return redirect()->back()->with('error_swal', 'No employee subtask found.');
    }
    
    $employeeSubtask = $subtask->employeeSubtask;

    $leadCount = (int) $subtask->lead ?? 1;
    $leadValues = range(1, $leadCount);

    // Check task type
    $isCallCenterPos = $subtask->task_type === 'cell_center_pos';
    $isCallCenterAccount = $subtask->task_type === 'cell_center_accounts';

    return view('employee.subtasks_update', compact('subtask', 'employeeSubtask', 'leadValues', 'isCallCenterPos', 'isCallCenterAccount'));
}

public function updatePos(Request $request, $id)
{
    $subtask = Subtask::findOrFail($id);
    
    // Check if employeeSubtask exists
    if (!$subtask->employeeSubtask) {
        return redirect()->back()->with('error_swal', 'No employee subtask found.');
    }
    
    $employeeSubtask = $subtask->employeeSubtask;

    // Validate the request
    $validator = Validator::make($request->all(), [
        'lead' => 'required|integer|min:1',
        'status' => 'required|in:pending,in_progress,completed',
        'comment' => 'nullable|string',
        'name' => 'nullable|string|max:255',
        'business_name' => 'nullable|string|max:255',
        'business_number' => 'nullable|string|max:20',
        'personal_number' => 'nullable|string|max:20',
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
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg,pdf|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput()->with('error_swal', 'Validation failed. Please check your inputs.');
    }

    $lead = $request->input('lead');

    // Validate lead number
    if ($lead < 1 || $lead > ($subtask->lead ?? 1)) {
        return redirect()->back()->with('error_swal', 'Invalid lead number.');
    }

    // Update EmployeeSubtask
    $employeeSubtask->comment = $request->input('comment');
    $employeeSubtask->status = $request->input('status');
    $employeeSubtask->save();

    // Update or create CellCenterPos record
    $posData = CellCenterPos::updateOrCreate(
        ['subtask_id' => $subtask->id, 'lead' => $lead],
        [
            'name' => $request->input('name'),
            'comment' => $request->input('comment'),
            'status' => $request->input('status'),
            'business_name' => $request->input('business_name'),
            'business_number' => $request->input('business_number'),
            'personal_number' => $request->input('personal_number'),
            'personal_email' => $request->input('personal_email'),
            'business_email' => $request->input('business_email'),
            'address' => $request->input('address'),
            'provider' => $request->input('provider'),
            'category_pos' => $request->input('category_pos'),
            'pos_type' => $request->input('pos_type'),
            'debt' => $request->input('debt'),
            'credit' => $request->input('credit'),
            'rental' => $request->input('rental'),
            'business_type' => $request->input('business_type'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'employee_id' => Auth::guard('employee')->user()->id, // Fixed to use ID
        ]
    );

    if ($request->hasFile('attachments')) {
        $uploadedFiles = $posData->attachments ?? [];
        foreach ($request->file('attachments') as $file) {
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'subtask_attachments',
                'resource_type' => in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : (in_array($file->getClientOriginalExtension(), ['mp3', 'wav', 'ogg']) ? 'raw' : 'image'),
            ]);
            $uploadedFiles[] = $uploadedFile->getSecurePath();
        }
        $posData->attachments = $uploadedFiles; // Fixed to store in posData
        $posData->save();
    }

    // Update cell_center_pos_ids array in Subtask
    $posIds = $subtask->cell_center_pos_ids ?? [];
    $posIds[$lead - 1] = $posData->id; // Store ID at lead index (0-based)
    $subtask->cell_center_pos_ids = array_values(array_filter($posIds)); // Remove nulls and reindex
    $subtask->save();

    return redirect()->back()->with('success_swal', 'Subtask updated successfully!');
}

public function updateAccount(Request $request, $id)
{
    $subtask = Subtask::findOrFail($id);
    
    // Check if employeeSubtask exists
    if (!$subtask->employeeSubtask) {
        return redirect()->back()->with('error_swal', 'No employee subtask found.');
    }
    
    $employeeSubtask = $subtask->employeeSubtask;

    // Validate the request
    $validator = Validator::make($request->all(), [
        'lead' => 'required|integer|min:1',
        'status' => 'required|in:pending,in_progress,completed',
        'comment' => 'nullable|string',
        'driving_license' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'business_number' => 'nullable|string|max:20',
        'corporation_number' => 'nullable|string|max:20',
        'corporation_email' => 'nullable|email|max:255',
        'corporation_documents' => 'nullable|string',
        'previous_history' => 'nullable|string',
        'fees' => 'nullable|numeric',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg,pdf|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput()->with('error_swal', 'Validation failed. Please check your inputs.');
    }

    $lead = $request->input('lead');

    // Validate lead number
    if ($lead < 1 || $lead > ($subtask->lead ?? 1)) {
        return redirect()->back()->with('error_swal', 'Invalid lead number.');
    }

    // Update EmployeeSubtask
    $employeeSubtask->comment = $request->input('comment');
    $employeeSubtask->status = $request->input('status');
    $employeeSubtask->save();

    // Update or create CellCenterAccount record
    $accountData = CellCenterAccount::updateOrCreate(
        ['subtask_id' => $subtask->id, 'lead' => $lead],
        [
            'comment' => $request->input('comment'),
            'status' => $request->input('status'),
            'driving_license' => $request->input('driving_license'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'business_number' => $request->input('business_number'),
            'corporation_number' => $request->input('corporation_number'),
            'corporation_email' => $request->input('corporation_email'),
            'corporation_documents' => $request->input('corporation_documents'),
            'previous_history' => $request->input('previous_history'),
            'fees' => $request->input('fees'),
            'employee_id' => Auth::guard('employee')->user()->id, // Fixed to use ID
        ]
    );

    if ($request->hasFile('attachments')) {
        $uploadedFiles = $accountData->attachments ?? [];
        foreach ($request->file('attachments') as $file) {
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'subtask_attachments',
                'resource_type' => in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : (in_array($file->getClientOriginalExtension(), ['mp3', 'wav', 'ogg']) ? 'raw' : 'image'),
            ]);
            $uploadedFiles[] = $uploadedFile->getSecurePath();
        }
        $accountData->attachments = $uploadedFiles; // Fixed to store in accountData
        $accountData->save();
    }

    // Update cell_center_account_ids array in Subtask
    $accountIds = $subtask->cell_center_account_ids ?? [];
    $accountIds[$lead - 1] = $accountData->id; // Store ID at lead index (0-based)
    $subtask->cell_center_account_ids = array_values(array_filter($accountIds)); // Remove nulls and reindex
    $subtask->save();

    return redirect()->back()->with('success_swal', 'Subtask updated successfully!');
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
