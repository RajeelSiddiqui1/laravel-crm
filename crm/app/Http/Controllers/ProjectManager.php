<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ProjectManager as ModelsProjectManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $project_manager = new ModelsProjectManager();
        $project_manager->name = $request->name;
        $project_manager->email = $request->email;
        $project_manager->phone = $request->phone;
        $project_manager->password = bcrypt($request->password);
        $project_manager->department_id = $request->department_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/project_managers'), $imageName);
            $project_manager->image = $imageName;
        }

        if ($project_manager->save()) {
            Mail::to($project_manager->email)->send(new AuthMail($project_manager));

            session()->flash('success', 'Project Manager registered successfully.');
            return redirect()->route('project_manager.register');
        } else {
            session()->flash('error', 'Failed to register Project Manager.');
            return redirect()->back();
        }
    }

    function loginview()
    {
        return view('project_manager.login');
    }

    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('welcome');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }
}
