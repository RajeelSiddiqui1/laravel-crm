<?php

namespace App\Http\Controllers;

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
            return redirect()->back()->withErrors($validator)->withInput();
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

            session()->flash('success', 'Team Lead registered successfully.');
            return redirect()->route('welcome');
        }

        session()->flash('error', 'Failed to register Team Lead.');
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('employee')->attempt($credentials)) {
            return redirect()->route('employee.home')->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    function tokenLogin($token)
    {
        $employee = ModelsEmployee::where('login_token', $token)->first();

        if (!$employee) {
            return redirect()->route('employee.login')->with('error', 'Invalid or expired login token.');
        }

        Auth::guard('employee')->login($employee);
        $employee->login_token = null;
        $employee->save();

        return redirect()->route('employee.home')->with('success', 'Logged in successfully via token.');
    }

    function logout()
    {
        Auth::guard('employee')->logout();
        return redirect()->route('employee.login')->with('success', 'Logged out successfully');
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

        return back()->with('success', 'Profile updated successfully!');
    }


    function team_task_view()
    {
        $employee = Auth::guard('employee')->user();

        $tasks = OnwerTask::with(['employee', 'department'])
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

    public function edit_subtask($id)
    {
        $subtask = Subtask::findOrFail($id);
        return view('employee.subtasks_update', compact('subtask'));
    }




    public function update_subtask(Request $request, $id)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'attachmentss.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,mp3,wav,ogg,pdf,doc,docx,xls,xlsx,txt|max:102400',
        ]);

        $subtask = Subtask::findOrFail($id);
        $subtask->comment = $request->comment;
        $subtask->status = $request->status;

        $existingattachmentss = $subtask->attachmentss ?? [];

        if ($request->hasFile('attachmentss')) {
            foreach ($request->file('attachmentss') as $file) {
                try {
                    $publicId = 'subtask_attachmentss/' . uniqid() . '_' . $file->getClientOriginalName();

                    $uploaded = Cloudinary::uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'public_id' => $publicId,
                            'resource_type' => 'auto',
                            'overwrite' => false
                        ]
                    );

                    $existingattachmentss[] = $uploaded['secure_url'];
                } catch (\Exception $e) {
                    Log::error('Upload failed: ' . $e->getMessage());
                    return redirect()->route('employee.subtasks')->with('error', 'attachments upload failed.');
                }
            }
        }

        $subtask->attachmentss = $existingattachmentss;
        $subtask->save();

        return redirect()->route('employee.subtasks')->with('success', 'Subtask updated successfully.');
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



    #message
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
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $message = new Message();
        $message->content = $request->content;
        $message->receiver_id = $request->receiver_id;

        if (Auth::guard('team_lead')->check()) {
            $message->sender_id = Auth::guard('team_lead')->id();
        } elseif (Auth::guard('employee')->check()) {
            $message->sender_id = Auth::guard('employee')->id();
        } else {
            return redirect()->back()->with('error', 'Unauthorized sender');
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
                Log::error('attachments upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'attachments upload failed.');
            }
        }

        if ($message->save()) {
            return redirect()->back()->with('success', 'Message sent successfully.');
        }

        return redirect()->back()->with('error', 'Message not sent.');
    }
}
