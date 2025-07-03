<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ProjectManager;
use App\Models\ProjectOwner;
use App\Models\TeamLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
