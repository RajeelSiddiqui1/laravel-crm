<?php

namespace App\Http\Controllers;

use App\Models\CellCenterAccount;
use App\Models\CellCenterPos;
use App\Models\SharedTask;
use App\Models\Visitor;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery\Generator\StringManipulation\Pass\Pass;

class VisitorController extends Controller
{
     function loginview()
    {
        return view('visitor.login');
    }

   public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:3',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->witherror_swals($validator)->withInput();
    }

    // Visitor ko direct DB se fetch karo
    $visitor = Visitor::where('email', $request->email)->first();

    if ($visitor->password) {
        // ✅ Login karwao manually
        Auth::guard('visitor')->login($visitor);

        return redirect()->route('visitor.home')->with('success_swal', 'Login successfully');
    }

    return redirect()->back()->with('error_swal', 'Invalid login credentials');
}

     function logout()
    {
        Auth::guard('visitor')->logout();
        return redirect()->route('visitor.login')->with('success_swal', 'Logged out successfully');
    }

   

    function home()
    {
        $visitor = Auth::guard('visitor')->user();
        return view('visitor.home', compact('visitor'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\visitor $visitor */
        $visitor = Auth::guard('visitor')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:visitors,email,' . $visitor->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $visitor->name = $request->name;
        $visitor->email = $request->email;


        if ($request->hasFile('image')) {
            $oldImage = public_path('images/visitors/' . $visitor->image);

            if ($visitor->image && file_exists($oldImage)) {
                @unlink($oldImage);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/visitors'), $imageName);
            $visitor->image = $imageName;
        }

        $visitor->save();

        return back()->with('success_swal', 'Profile updated successfully!');
    }

    function visitview(){
        return view("visitor.visit_list");
    }

  public function showSharedTasks()
{
    // Visitor login check
    $user = Auth::guard('visitor')->user();
    if (!$user) {
        return redirect()->route('visitor.login')->with('error', 'Please login first.');
    }

    // Fetch all shared tasks for the visitor
    $sharedTasks = SharedTask::where('visitor_id', $user->id)->get();

    $posResults = [];
    $accountResults = [];

    foreach ($sharedTasks as $shared) {
        if ($shared->cell_center_pos_id) {
            $pos = CellCenterPos::find($shared->cell_center_pos_id);
            if ($pos) {
                // Attach shared_task_id and status to pos results
                $pos->shared_task_id = $shared->id;
                $pos->shared_status = $shared->status;
                $posResults[] = $pos;
            }
        } elseif ($shared->cell_center_account_id) {
            $account = CellCenterAccount::find($shared->cell_center_account_id);
            if ($account) {
                // Attach shared_task_id and status to account results
                $account->shared_task_id = $shared->id;
                $account->shared_status = $shared->status;
                $accountResults[] = $account;
            }
        }
    }

    return view('visitor.visit_list', compact('sharedTasks', 'posResults', 'accountResults'));
}

public function lead_info($id)
{
    // Find the shared task by ID
    $shared_task = SharedTask::findOrFail($id);

    // Determine if it's a POS or Account and fetch the related record
    $record = null;
    $type = null;

    if ($shared_task->cell_center_pos_id) {
        $record = CellCenterPos::find($shared_task->cell_center_pos_id);
        $type = 'pos';
    } elseif ($shared_task->cell_center_account_id) {
        $record = CellCenterAccount::find($shared_task->cell_center_account_id);
        $type = 'account';
    }

    return view('visitor.lead_info', compact('shared_task', 'record', 'type'));
}


public function update_lead_info(Request $request, $id)
{
    $sharedTask = SharedTask::findOrFail($id);

    $request->validate([
        'status' => 'required|in:pending,deployed,on_leave,inactive', // Updated to match ENUM
        'comment' => 'nullable|string|max:5000',
        'attachments' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mp3,wav,ogg,pdf,xls,xlsx,csv,doc,docx|max:10240',
    ]);

    $attachmentUrl = $sharedTask->attachments;

    if ($request->hasFile('attachments')) {
        try {
            $uploadedFile = Cloudinary::uploadApi()->upload(
                $request->file('attachments')->getRealPath(),
                [
                    'folder' => 'shared_tasks',
                    'resource_type' => 'auto',
                ]
            );
            $attachmentUrl = $uploadedFile['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors([
                'attachments' => 'Failed to upload file: ' . $e->getMessage(),
            ]);
        }
    }

    $sharedTask->update([
        'status' => $request->status,
        'comment' => $request->comment,
        'attachments' => $attachmentUrl,
    ]);

    return redirect()->route('visitor.sharedtask.view')
        ->with('success', 'Shared task updated successfully.');
}




     public function showPos($id)
    {
        $pos = CellCenterPos::findOrFail($id);
        return view('visitor.pos_detail', compact('pos'));
    }

    // Account Detail
    public function showAccount($id)
    {
        $account = CellCenterAccount::findOrFail($id);
        return view('visitor.account_detail', compact('account'));
    }



}
