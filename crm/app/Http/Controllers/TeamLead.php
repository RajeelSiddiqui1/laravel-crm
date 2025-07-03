<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthMail;
use App\Models\TeamLead as ModelsTeamLead;
use App\Models\Department;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamLead extends Controller
{
    function resgisterview()
    {
        $departments = Department::all(); // ✅ Fix: Get departments from correct model
        return view('team_lead.register', compact('departments'));
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:team_leads',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:3|confirmed',
            'department_id' => 'required|exists:departments,id', // ✅ single department
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $manager = new ModelsTeamLead();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        $manager->password = bcrypt($request->password);
        $manager->department_id = $request->department_id; // ✅ assign single department

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/team_leads'), $imageName);
            $manager->image = $imageName;
        } else {
            $randomId = rand(1, 30);
            $imageContent = file_get_contents("https://avatar.iran.liara.run/public/$randomId");
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
        $team_lead = ModelsTeamLead::where('login_token', $token)->first();

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
}
