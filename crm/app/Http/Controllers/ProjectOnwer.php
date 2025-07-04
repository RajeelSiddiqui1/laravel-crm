<?php

namespace App\Http\Controllers;

use App\Mail\EditTask;
use App\Mail\TaskAssignedMail;
use App\Mail\TaskDeletedMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OnwerTask;
use App\Models\ProjectManager;
use App\Models\ProjectOwner;
use App\Models\TeamLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

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

        $user = ProjectOwner::where('email', $request->email)->first();

        if ($user && $user->password === $request->password) {
            Auth::guard('project_owner')->login($user);
            return redirect()->route('project_owner.home');
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
    }

    function logout()
    {
        Auth::guard('project_owner')->logout();
        return redirect()->route('project_owner.login');
    }

    function home()
    {

        return view('project_owner.home');
    }

    function project_manager_view()
    {
        $managers = ProjectManager::with('department')->get();
        return view('project_owner.project_managers', ['managers' => $managers]);
    }

    function employee_view()
    {
        $employees = Employee::with('department')->get();
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

        return redirect()->route('project_owner.departments')->with('success', 'Department created successfully');
    }

    function department_edit_view($id)
    {
        $department = Department::find($id);
        if ($department) {
            return view('project_owner.departments_edit', ['department' => $department]);
        } else {
            return redirect()->route('project_owner.departments')->with('error', 'Department not found');
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
            return redirect()->route('project_owner.departments')->with('success', 'Department updated successfully');
        } else {
            return redirect()->route('project_owner.departments')->with('error', 'Department not found');
        }
    }

    function department_delete($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
            return redirect()->route('project_owner.departments')->with('success', 'Department deleted successfully');
        } else {
            return redirect()->route('project_owner.departments')->with('error', 'Department not found');
        }
    }

    function task_view()
    {
        $tasks = OnwerTask::with(['department', 'projectManager'])->get();
        return view('project_owner.tasks', ['tasks' => $tasks]);
    }

    function task_detail($id)
    {
        $task = OnwerTask::with(['department', 'projectManager'])->find($id);
        if ($task) {
            return view('project_owner.task_detail', ['task' => $task]);
        } else {
            return redirect()->route('project_owner.task_detail')->with('error', 'Task not found');
        }
    }

    function tasks_createview()
    {
        $departments = Department::with('projectManagers')->get();
        return view('project_owner.tasks_create', compact('departments'));;
    }

    function tasks_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'client_name' => 'required',
            'description' => 'required',
            'client_email' => 'required|email',
            'client_contact' => 'required',
            'project_manager_id' => 'required|exists:project_managers,id',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $task = new OnwerTask();
        $task->name = $request->name;
        $task->client_name = $request->client_name;
        $task->description = $request->description;
        $task->client_email = $request->client_email;
        $task->client_contact = $request->client_contact;
        $task->department_id = $request->department_id;
        $task->project_manager_id = $request->project_manager_id;
        $task->manager_email = ProjectManager::find($request->project_manager_id)->email;
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
     
        if ($task->save()) {

            Mail::to($task->manager_email)->send(new TaskAssignedMail($task));

            return redirect()->route('project_owner.task')->with('success', 'Task created successfully');
        } else {
            return redirect()->route('project_owner.task')->with('error', 'Failed to create task');
        }
    }

    public function edit($id)
    {
        $task = OnwerTask::findOrFail($id);
        $departments = Department::with('projectManagers')->get();
        return view('project_owner.task_edit', compact('task', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'client_name'        => 'required|string|max:255',
            'description'        => 'required|string',
            'client_email'       => 'required|email',
            'client_contact'     => 'required|string|max:20',
            'project_manager_id' => 'required|exists:project_managers,id',
            'manager_email'      => 'required|email',
            'start_date'         => 'required|date',
            'deadline'           => 'required|date|after_or_equal:start_date',
            'priority'           => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $task = OnwerTask::findOrFail($id);

        $task->update([
            'name'               => $request->name,
            'client_name'        => $request->client_name,
            'description'        => $request->description,
            'client_email'       => $request->client_email,
            'client_contact'     => $request->client_contact,
            'project_manager_id' => $request->project_manager_id,
            'manager_email'      => $request->manager_email,
            'start_date'         => $request->start_date,
            'deadline'           => $request->deadline,
            'priority'           => $request->priority,
        ]);

        Mail::to($task->manager_email)->send(new EditTask($task));
        return redirect()->route('project_owner.task', $id)->with('success', 'Task updated successfully.');
    }

     function destroy($id)
    {
        $task = OnwerTask::findOrFail($id);
        $task->delete();
        Mail::to($task->manager_email)->send(new TaskDeletedMail($task));
        return redirect()->route('project_owner.task')->with('success', 'Task deleted successfully.');
    }
}
