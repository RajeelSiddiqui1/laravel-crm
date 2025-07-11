<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OwnerTask;
use App\Models\GroupMessage;
use App\Models\OnwerTask;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'attachments' => 'nullable|file|max:102400',
        ]);

        $message = new GroupMessage();
        $message->owner_task_id = $request->owner_task_id;
        $message->content = $request->content;

        // Detect sender info (team_lead, employee, or project_manager)
        if (Auth::guard('team_lead')->check()) {
            $message->sender_id = Auth::guard('team_lead')->id();
            $message->sender_type = 'team_lead';
        } elseif (Auth::guard('employee')->check()) {
            $message->sender_id = Auth::guard('employee')->id();
            $message->sender_type = 'employee';
        } elseif (Auth::guard('project_manager')->check()) {
            $message->sender_id = Auth::guard('project_manager')->id();
            $message->sender_type = 'project_manager';
        } else {
            return redirect()->back()->with('error', 'Unauthorized sender');
        }

        $message->receiver_ids = json_encode($this->getReceiverIds($request->owner_task_id, $message->sender_id));

        if ($request->hasFile('attachments')) {
            $uploaded = Cloudinary::upload($request->file('attachments')->getRealPath(), [
                'resource_type' => 'auto'
            ]);
            $message->attachments = $uploaded->getSecurePath();
        }

        $message->save();
        return redirect()->back()->with('success', 'Message sent successfully');
    }

    private function getReceiverIds($taskId, $senderId)
    {
        $task = OnwerTask::findOrFail($taskId);
        $receivers = [];

        if ($task->employee_id) $receivers[] = $task->employee_id;
        if ($task->project_manager_id) $receivers[] = $task->project_manager_id;
        if ($task->team_lead_id) $receivers[] = $task->team_lead_id;

        return array_filter($receivers, fn($id) => $id != $senderId);
    }
}
