<?php

namespace App\Http\Controllers;

use App\Models\OnwerTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\TeamLead; // Corrected: Renamed alias to direct import (assuming Controller is renamed)
use App\Models\Department;
use App\Models\Employee; // Ensure Employee model is imported for clarity and consistency
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamLeadController extends Controller // Corrected: Changed class name to TeamLeadController
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $manager = new TeamLead(); // Corrected: Using the direct TeamLead model name
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
            $imageContent = @file_get_contents("https://avatar.iran.liara.run/public/$randomId"); // Suppress warning if URL fails
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

            session()->flash('success', 'Team Lead registered successfully.');
            return redirect()->route('welcome');
        }

        session()->flash('error', 'Failed to register Team Lead.');
        return redirect()->back()->withInput();
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('team_lead')->attempt($credentials)) {
            return redirect()->route('team_lead.home')->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    function tokenLogin($token)
    {
        $team_lead = TeamLead::where('login_token', $token)->first(); // Corrected: Using direct TeamLead model name

        if (!$team_lead) {
            return redirect()->route('team_lead.login')->with('error', 'Invalid or expired login token.');
        }

        Auth::guard('team_lead')->login($team_lead);
        $team_lead->login_token = null;
        $team_lead->save();

        return redirect()->route('team_lead.home')->with('success', 'Logged in successfully via token.');
    }

    function logout()
    {
        Auth::guard('team_lead')->logout();
        return redirect()->route('team_lead.login')->with('success', 'Logged out successfully');
    }

    function home()
    {
        return view('team_lead.home');
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

        $employee->save();

        return back()->with('success', 'Profile updated successfully!');
    }
    public function manager_tasks()
    {
        $teamLead = Auth::guard('team_lead')->user();

        // Get department_id of the logged-in team lead
        $departmentId = $teamLead->department_id;

        // Fetch employees from the same department
        $employees = Employee::where('department_id', $departmentId)->get();

        // Fetch tasks if needed
        $tasks = OnwerTask::with(['teamLead', 'department'])
            ->where('team_lead_id', $teamLead->id)
            ->get();

        return view('team_lead.manager_tasks', compact('tasks', 'employees'));
    }





    public function assignEmployees(Request $request, OnwerTask $task)
    {
        $request->validate([
            'employee_id' => 'nullable|array',
            'employee_id.*' => 'exists:employees,id',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        if ($task->team_lead_id !== $teamLead->id) {
            abort(403, 'Unauthorized action. This task is not assigned to you.');
        }

        if ($request->has('employee_id')) {
            foreach ($request->employee_id as $employeeId) {
                $employee = Employee::find($employeeId);
                if (!$employee || $employee->department_id !== $teamLead->department_id) {
                    return back()->with('error', 'You can only assign employees from your own department.');
                }
            }
        }

        // Save as comma-separated string
        $task->employee_id = implode(',', $request->input('employee_id', []));
        $task->save();

        return back()->with('success', 'Employees assigned successfully!');
    }


    function updateStatus(Request $request, OnwerTask $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $teamLead = Auth::guard('team_lead')->user();

        if ($task->team_lead_id !== $teamLead->id) {
            abort(403, 'Unauthorized action. This task is not assigned to you.');
        }

        $task->status = $request->status;
        $task->save();

        return back()->with('success', 'Task status updated successfully!');
    }




    function manager_tasks_detail($id)
    {
        $task = OnwerTask::findOrFail($id);
        return view('team_lead.manager_tasks_detail', compact('task'));
    }
}
