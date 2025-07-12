<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OwnerTask;
use App\Models\GroupMessage;
use App\Models\OnwerTask;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use App\Events\GroupMessageSent;

class GroupChatController extends Controller
{
    public function index($ownerTaskId)
    {
        $task = OnwerTask::findOrFail($ownerTaskId);
        $messages = GroupMessage::where('owner_task_id', $ownerTaskId)->orderBy('created_at')->get();
        return view('chat.group_chat', compact('task', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'owner_task_id' => 'required|exists:owner_tasks,id',
            'content' => 'nullable|string',
            'receiver_id' => 'nullable|integer',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm,mp3,wav,ogg,pdf,doc,docx,xls,xlsx,txt|max:102400',
        ]);

        $message = new GroupMessage();
        $message->owner_task_id = $request->owner_task_id;
        $message->content = $request->content;

        if (Auth::guard('team_lead')->check()) {
            $message->sender_id = Auth::guard('team_lead')->id();
            $message->sender_type = 'team_lead';
        } elseif (Auth::guard('employee')->check()) {
            $message->sender_id = Auth::guard('employee')->id();
            $message->sender_type = 'employee';
        } elseif (Auth::guard('project_manager')->check()) {
            $message->sender_id = Auth::guard('project_manager')->id();
            $message->sender_type = 'project_manager';
        } elseif (Auth::guard('project_owner')->check()) {
            $message->sender_id = Auth::guard('project_owner')->id();
            $message->sender_type = 'project_owner';
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->filled('receiver_id')) {
            $message->receiver_ids = json_encode([$request->receiver_id]);
        } else {
            $message->receiver_ids = json_encode($this->getReceiverIds($request->owner_task_id, $message->sender_id));
        }

        if ($request->hasFile('attachments')) {
            try {
                $file = $request->file('attachments');
                $publicId = 'chat_attachments/' . uniqid() . '_' . $file->getClientOriginalName();
                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'public_id' => $publicId,
                    'resource_type' => 'auto',
                    'overwrite' => false
                ]);
                $message->attachments = $uploaded['secure_url'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Upload failed.'], 500);
            }
        }

        $message->save();
       if( event(new GroupMessageSent($message))){
        return response()->json(['success'=> 'sent'],0);
       }

         return redirect()->back()->with('success', 'Message sent successfully.');
    }

    private function getReceiverIds($taskId, $senderId)
    {
        $task = OnwerTask::findOrFail($taskId);
        $receivers = [];
        if ($task->employee_id) $receivers[] = $task->employee_id;
        if ($task->project_manager_id) $receivers[] = $task->project_manager_id;
        if ($task->team_lead_id) $receivers[] = $task->team_lead_id;
        if ($task->project_owner_id) $receivers[] = $task->project_owner_id;
        return array_filter($receivers, fn($id) => $id != $senderId);
    }

}
