<?php

namespace App\Http\Controllers;

use App\Mail\EditTask;
use App\Mail\TaskAssignedMail;
use App\Mail\TaskDeletedMail;
use App\Mail\TaskUpdatedMail;
use App\Models\AccountHST;
use App\Models\AccountT1;
use App\Models\AccountT2;
use App\Models\CellCenterAccount;
use App\Models\CellCenterPos;
use App\Models\ClientDetail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ManagerOperation;
use App\Models\Notification;
use App\Models\OnwerTask;
use App\Models\ProjectManager;
use App\Models\ProjectOwner;
use App\Models\SharedTask;
use App\Models\Subtask;
use App\Models\TeamLead;
use App\Models\Visitor;
use App\Notifications\OwnerTaskAssign;
use App\Notifications\OwnerTaskEdit;
use Cloudinary\Cloudinary as CloudinaryCloudinary;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

use function Laravel\Prompts\password;
use function PHPUnit\Framework\fileExists;
use function Termwind\render;

class ProjectOnwer extends Controller
{
    function loginview()
    {
        return view('project_owner.login');
    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $creditionals = $request->only('email', 'password');

        if (Auth::guard('project_owner')->attempt($creditionals)) {
            return redirect()->route('project_owner.home')->with('success_swal', 'Login successfully');
        } else {
            return redirect()->back()->with('error_swal', 'Invalid login credentials');
        }
    }

    function logout()
    {
        Auth::guard('project_owner')->logout();
        return redirect()->route('project_owner.login');
    }



    function home()
    {

        $owner = Auth::guard('project_owner')->user();
        return view('project_owner.home', compact('owner'));
    }

    function profile_update(Request $request)
    {
        /** @var  \App\Models\ProjectOwner owner **/
        $owner = Auth::guard('project_owner')->user();

        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_leads,email,' . $owner->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if (!$validator) {
            return redirect()->back()->withErrors('validation', 'error in validation')->withInput();
        }

        $owner->name = $request->name;
        $owner->email = $request->email;

        if ($request->hasFile('image')) {
            $oldImage = public_path('images/project_owner/' . $owner->image);
            if ($owner->image && file_exists($oldImage)) {
                @unlink($oldImage);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/project_owner/'), $imageName);
            $owner->image = $imageName;
        }


        if ($request->filled('password')) {
            $owner->password = bcrypt($request->password);
        }

        if ($owner->save()) {
            return redirect()->back()->with('success_swal', 'profile updated successfully');
        } else {
            return redirect()->back()->with('error_swal', 'Error in profile update');
        }
    }


    function project_manager_view()
    {
        $managers = ProjectManager::all();

        foreach ($managers as $manager) {
            $deptIds = $manager->department_ids; // field ka naam departments_ids hai

            // Ensure hamesha array mile
            if (is_string($deptIds)) {
                $deptIds = json_decode($deptIds, true);
            }

            if (empty($deptIds)) {
                $deptIds = [];
            }

            // Departments fetch karo
            $departments = Department::whereIn('id', $deptIds)->pluck('name')->toArray();

            // Extra property set kar dete hain
            $manager->departments_list = $departments;
        }

        return view('project_owner.project_managers', compact('managers'));
    }

    function employee_view()
    {
        $employees = Employee::all();
        return view('project_owner.employees', ['employees' => $employees]);
    }

    public function teamLeadsView()
    {
        $teamLeads = TeamLead::with('department')->get();
        return view('project_owner.team_leads', compact('teamLeads'));
    }

    function department_view()
    {
        $departments = Department::all();
        return view('project_owner.departments', ['departments' => $departments]);
    }

    function department_create_view()
    {
        return view('project_owner.departments_create');
    }

    function department_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:departments',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        return redirect()->route('project_owner.departments')->with('success_swal', 'Department created successfully');
    }

    function department_edit_view($id)
    {
        $department = Department::find($id);
        if ($department) {
            return view('project_owner.departments_edit', ['department' => $department]);
        } else {
            return redirect()->route('project_owner.departments')->with('error_swal', 'Department not found');
        }
    }

    function department_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:departments,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department = Department::find($request->id);
        if ($department) {
            $department->name = $request->name;
            $department->save();
            return redirect()->route('project_owner.departments')->with('success_swal', 'Department updated successfully');
        } else {
            return redirect()->route('project_owner.departments')->with('error_swal', 'Department not found');
        }
    }

    function department_delete($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
            return redirect()->route('project_owner.departments')->with('success_swal', 'Department deleted successfully');
        } else {
            return redirect()->route('project_owner.departments')->with('error_swal', 'Department not found');
        }
    }

    function task_view()
    {
        $tasks = OnwerTask::with(['department', 'projectManager'])
            ->whereJsonLength('managers', '>', 0) // array me koi bhi id exist karti ho
            ->get();

        return view('project_owner.tasks', ['tasks' => $tasks]);
    }


    function task_detail($id)
    {
        $task = OnwerTask::with(['department', 'projectManager'])->find($id);
        if ($task) {
            return view('project_owner.task_detail', ['task' => $task]);
        } else {
            return redirect()->route('project_owner.task_detail')->with('error_swal', 'Task not found');
        }
    }





    public function tasks_createview()
    {
        $managers = ProjectManager::all()->map(function ($manager) {
            $departmentNames = [];

            if (!empty($manager->department_ids)) {
                $departments = Department::whereIn('id', $manager->department_ids)->pluck('name')->toArray();
                $departmentNames = $departments;
            }

            return [
                'id' => $manager->id,
                'name' => $manager->name ?? 'Manager ' . $manager->id,
                'departments' => $departmentNames,
            ];
        })->toArray();

        return view('project_owner.tasks_create', compact('managers'));
    }
    public function getProjectManagers($departmentId)
    {
        $managers = ProjectManager::whereJsonContains('department_ids', (string) $departmentId)
            ->get(['id', 'name']);

        return response()->json($managers);
    }



    function tasks_create(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'managers' => 'nullable|array',
            'managers.*' => 'exists:project_managers,id',
            'audio_file' => 'nullable|string',
        ]);

        $task = new OnwerTask();
        $task->client_name = $validated['client_name'];
        $task->managers = json_encode($validated['managers'] ?? []);

        if ($request->filled('audio_file')) {
            try {
                [, $data] = explode(';', $request->audio_file);
                [, $base64Data] = explode(',', $data);
                $binary = base64_decode($base64Data);

                $tempPath = tempnam(sys_get_temp_dir(), 'audio_');
                file_put_contents($tempPath, $binary);

                $uploaded = Cloudinary::uploadApi()->upload($tempPath, [
                    'folder' => 'task_audio',
                    'resource_type' => 'video',
                    'public_id' => 'task_audio/' . uniqid('audio_')
                ]);

                $task->audio_url = $uploaded['secure_url'];

                unlink($tempPath);
            } catch (\Exception $e) {
                Log::error('Cloudinary audio upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error_swal', 'Audio upload failed')->withInput();
            }
        }

        // ✅ First save task to DB before sending emails
        $task->save();

        // 📧 Send emails after task is saved
        try {
            $managerIds = json_decode($task->managers, true);
            if (!empty($managerIds)) {
                $managers = ProjectManager::whereIn('id', $managerIds)->pluck('email')->toArray();

                foreach ($managers as $email) {
                    Mail::to($email)->send(new TaskAssignedMail($task));
                }
            }
        } catch (\Exception $e) {
            Log::error('Task creation email send failed: ' . $e->getMessage());
            // Continue execution even if email fails
        }

        return redirect()->route('project_owner.task')->with('success_swal', 'Task created successfully & emails sent!');
    }



    public function edit($id)
    {
        $task = OnwerTask::findOrFail($id);
        $managers = ProjectManager::all()->map(function ($manager) {
            return [
                'id' => $manager->id,
                'name' => $manager->name ?? 'Manager ' . $manager->id,
            ];
        })->toArray();

        return view('project_owner.task_edit', compact('task', 'managers'));
    }

    // Update an existing task
    public function update(Request $request, $id)
    {
        $task = OnwerTask::findOrFail($id);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'managers' => 'nullable|array',
            'managers.*' => 'exists:project_managers,id',
            'audio_file' => 'nullable|string',
        ]);

        $task->client_name = $validated['client_name'];
        $task->managers = json_encode($validated['managers'] ?? []);
        $task->status = $task->status; // Preserve existing status

        // 🎧 Handle audio re-upload (optional)
        if ($request->filled('audio_file')) {
            try {
                [, $data] = explode(';', $request->audio_file);
                [, $base64Data] = explode(',', $data);
                $binary = base64_decode($base64Data);

                $tempPath = tempnam(sys_get_temp_dir(), 'audio_');
                file_put_contents($tempPath, $binary);

                $publicId = 'task_audio/task_' . $task->id;

                $uploaded = Cloudinary::uploadApi()->upload($tempPath, [
                    'folder' => 'task_audio',
                    'resource_type' => 'video',
                    'public_id' => $publicId,
                    'overwrite' => true,
                ]);

                $task->audio_url = $uploaded['secure_url'];

                unlink($tempPath);
            } catch (\Exception $e) {
                Log::error('Cloudinary audio upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error_swal', 'Audio upload failed')->withInput();
            }
        }

        $task->save();

        // 📧 Send email notification to all managers
        try {
            $managerIds = json_decode($task->managers, true);
            if (!empty($managerIds)) {
                $managers = ProjectManager::whereIn('id', $managerIds)->pluck('email')->toArray();

                foreach ($managers as $email) {
                    Mail::to($email)->send(new TaskUpdatedMail($task));
                }
            }
        } catch (\Exception $e) {
            Log::error('Task update email send failed: ' . $e->getMessage());
            // Continue execution even if email fails
        }

        return redirect()->route('project_owner.task')->with('success_swal', 'Task updated successfully & emails sent!');
    }


    // Delete a task

    public function destroy($id)
    {
        try {
            $task = OnwerTask::findOrFail($id);
            $deletedBy = 'Admin'; // 🔥 Always show as deleted by Admin

            // Decode managers from JSON
            $managerIds = json_decode($task->managers ?? '[]', true);
            $managers = ProjectManager::whereIn('id', $managerIds)->get();

            // Send email to each assigned manager
            foreach ($managers as $manager) {
                if (!empty($manager->email)) {
                    Mail::to($manager->email)->send(new TaskDeletedMail($task, $deletedBy));
                }
            }

            // Delete task from DB
            $task->delete();

            return redirect()->route('project_owner.tasks')
                ->with('success_swal', 'Task deleted successfully & email notifications sent!');
        } catch (\Exception $e) {
            Log::error('Delete Task Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'task_id' => $id
            ]);

            return redirect()->back()
                ->with('error_swal', 'Failed to delete task: ' . $e->getMessage());
        }
    }


    public function manager_task($id)
    {
        $tasks = OnwerTask::find($id);
        return view('project_owner.project_manager_tasks2', compact('tasks'));
    }

    public function taskFullDetails($id)
    {
        $task = OnwerTask::with(['teamLead', 'employee'])->findOrFail($id);
        return view('project_owner.full_detail', compact('task'));
    }

    public function subtask()
    {
        $subtasks = Subtask::with('employee', 'teamLead')->get();
        return view('project_owner.subtask', compact('subtasks'));
    }

    public function subtask_detail($id)
    {
        $subtask = Subtask::with('employee', 'teamLead')->findOrFail($id);
        $posRecords = $subtask->call_center_pos_ids
            ? CellCenterPos::whereIn('id', $subtask->call_center_pos_ids)->with('employee')->get()
            : collect();
        $accountRecords = $subtask->cell_center_account_ids
            ? CellCenterAccount::whereIn('id', $subtask->cell_center_account_ids)->with('employee')->get()
            : collect();
        $clientDetailRecords = $subtask->client_detail_ids
            ? CellCenterAccount::whereIn('id', $subtask->client_detail_ids)->with('employee')->get()
            : collect();
        $sharedTasks = SharedTask::where('subtask_id', $subtask->id)->get();

        return view('project_owner.subtask_detail', compact('subtask', 'posRecords', 'accountRecords', 'clientDetailRecords', 'sharedTasks'));
    }

    public function signed()
    {
        $shared_task = SharedTask::all();

        return view('project_owner.signed', compact('shared_task'));
    }




    public function visitors()
    {

        $visitors = Visitor::get();

        return view('project_owner.visitor', compact('visitors'));
    }

    public function create_visitor_view()
    {
        $departments = Department::get();
        return view('project_owner.create_visitor', compact('departments'));
    }

    public function create_visitor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:visitors,name',
            'email' => 'required|email|max:255|unique:visitors',
            'phone' => 'required|string|max:15',
            'department_id' => 'exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $visitors = new Visitor();
        $visitors->name = $request->name;
        $visitors->email = $request->email;
        $visitors->phone = $request->phone;

        // Set password as "name123"
        $firstWord = explode(' ', $request->name)[0]; // pehla word nikal lo
        $short = strtolower(substr($firstWord, 0, 1)); // pehle word ka first letter lowercase

        $visitors->password = $short . '123';


        // Save selected departments as JSON array
        $visitors->department_ids = (array) $request->department_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/visitors'), $imageName);
            $visitors->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
            if ($imageContent !== false) {
                $imageName = time() . '_auto.jpg';
                file_put_contents(public_path("images/visitors/$imageName"), $imageContent);
                $visitors->image = $imageName;
            }
        }

        if ($visitors->save()) {
            session()->flash('success_swal', 'Visitor created successfully.');
            return redirect()->route('project_owner.visitor');
        }

        session()->flash('error_swal', 'Failed to create Visitor.');
        return redirect()->back()->withInput();
    }

    public function allOwnerTasks()
    {
        // Fetch all owner tasks where project_manager_task is set (not null)
        $tasks = OnwerTask::with('projectManagerTask')
            ->whereNotNull('project_manger_task')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('project_owner.project_manager_tasks2', compact('tasks'));
    }


    public function all_manager_task()
    {
        $accountst1 = AccountT1::with(['department', 'teamLead'])->get();
        $accountst2 = AccountT2::with(['department', 'teamLead'])->get();
        $accountsthst = AccountHST::with(['department', 'teamLead'])->get();
        $manageroperation = ManagerOperation::with(['department', 'teamLead'])->get();

        return view('project_owner.project_manager_tasks', compact(
            'accountst1',
            'accountst2',
            'accountsthst',
            'manageroperation'
        ));
    }

    public function all_manager_task_detail($type, $id)
    {
        $account = null;
        $managerType = strtoupper($type);

        // Fetch the main account or operation
        if ($type === 't1') {
            $account = AccountT1::with(['department', 'teamLead'])->find($id);
        } elseif ($type === 't2') {
            $account = AccountT2::with(['department', 'teamLead'])->find($id);
        } elseif ($type === 'hst') {
            $account = AccountHST::with(['department', 'teamLead'])->find($id);
        } elseif ($type === 'operation') {
            $account = ManagerOperation::with(['department', 'teamLead'])->find($id);
        }

        // If account is not found, redirect with error
        if (!$account) {
            return redirect()->route('project_owner.manager_tasks')
                ->with('error_swal', 'Invalid account type or task not found.');
        }

        // Fetch all ManagerOperation tasks without any filter
        $managerOperations = ManagerOperation::orderBy('created_at', 'desc')->get();

        return view('project_owner.manager_task_detail', compact('account', 'managerType', 'managerOperations'));
    }
}
