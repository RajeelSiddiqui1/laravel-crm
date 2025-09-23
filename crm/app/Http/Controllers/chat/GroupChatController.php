<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\ManagerOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OwnerTask;
use App\Models\GroupMessage;
use App\Models\OnwerTask;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use App\Events\GroupMessageSent;
use App\Models\AccountHST;
use App\Models\AccountT1;
use App\Models\AccountT2;

class GroupChatController extends Controller
{
  public function index($id)
{
    $account = null;
    $accountType = null;

    // Check T1
    if ($accountT1 = AccountT1::find($id)) {
        $account = $accountT1;
        $accountType = 'T1';
    }
    // Check T2
    elseif ($accountT2 = AccountT2::find($id)) {
        $account = $accountT2;
        $accountType = 'T2';
    }
    // Check HST
    elseif ($accountHST = AccountHST::find($id)) {
        $account = $accountHST;
        $accountType = 'HST';
    }
    // Check Manager Operation
    elseif ($operation = ManagerOperation::find($id)) {
        $account = $operation;
        $accountType = 'ManagerOperation';
    } else {
        return redirect()->back()->with('error_swal', 'Account not found.');
    }

    // ⚡ Ab messages ko owner_task_id se hi filter karna padega
    $messages = GroupMessage::where('owner_task_id', $id)
        ->orderBy('created_at', 'asc')
        ->get();

    return view('chat.group_chat', compact('messages', 'account', 'accountType'));
}


    // Send message
public function send(Request $request)
{
    $request->validate([
        'account_type' => 'required|string|in:T1,T2,HST,ManagerOperation',
        'account_id'   => 'required|integer',
        'content'      => 'nullable|string',
        'receiver_id'  => 'nullable|integer',
        'attachments'  => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,mp3,wav,ogg,pdf,doc,docx,xls,xlsx,txt|max:102400',
    ]);

    $message = new GroupMessage();
    $message->account_type = $request->account_type;  // ✅ new fields
    $message->account_id   = $request->account_id;
    $message->content      = $request->content;

    // Detect sender
    if (Auth::guard('team_lead')->check()) {
        $message->sender_id   = Auth::guard('team_lead')->id();
        $message->sender_type = 'team_lead';
    } elseif (Auth::guard('employee')->check()) {
        $message->sender_id   = Auth::guard('employee')->id();
        $message->sender_type = 'employee';
    } elseif (Auth::guard('project_manager')->check()) {
        $message->sender_id   = Auth::guard('project_manager')->id();
        $message->sender_type = 'project_manager';
    } elseif (Auth::guard('project_owner')->check()) {
        $message->sender_id   = Auth::guard('project_owner')->id();
        $message->sender_type = 'project_owner';
    } else {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Receivers
    if ($request->filled('receiver_id')) {
        $message->receiver_ids = json_encode([$request->receiver_id]);
    } else {
        $message->receiver_ids = json_encode([]); // 🔹 For now keep empty or implement custom logic
    }

    // File upload (Cloudinary)
    if ($request->hasFile('attachments')) {
        try {
            $file = $request->file('attachments');
            $publicId = 'chat_attachments/' . uniqid() . '_' . $file->getClientOriginalName();

            $uploaded = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::uploadApi()->upload(
                $file->getRealPath(),
                [
                    'public_id'    => $publicId,
                    'resource_type'=> 'auto',
                    'overwrite'    => false,
                ]
            );

            $message->attachments = $uploaded['secure_url'];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed.'], 500);
        }
    }

    $message->save();

    // Fire event
    event(new GroupMessageSent($message));

    return response()->json(['success' => 'Message sent successfully.'], 200);
}


    // Get receivers (except sender)
    private function getReceiverIds($taskId, $senderId)
    {
        $task = OnwerTask::findOrFail($taskId);
        $receivers = [];

        if ($task->employee_id) $receivers[] = $task->employee_id;
        if ($task->project_manager_id) $receivers[] = $task->project_manager_id;
        if ($task->team_lead_id) $receivers[] = $task->team_lead_id;
        if ($task->project_owner_id) $receivers[] = $task->project_owner_id;

        // Exclude sender
        return array_filter($receivers, fn($id) => $id != $senderId);
    }
}
