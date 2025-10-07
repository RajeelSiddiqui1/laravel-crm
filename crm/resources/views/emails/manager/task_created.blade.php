@component('mail::message')
# 📋 New Task Assigned!

Hi **{{ $teamLead->name }}**,  
You’ve just received a new task assigned by **{{ $manager->name }}**.

---

### 🧾 Task Summary
- **Department:** {{ optional($task->department)->name ?? 'N/A' }}
- **Account Type:** {{ ucfirst($task->account_type ?? 'N/A') }}
- **Status:** {{ ucfirst($task->status) }}
- **Assigned By:** {{ $manager->name }}  
- **Manager Email:** {{ $manager->email }}

@if($task->created_at)
- **Created At:** {{ $task->created_at->format('d M Y, h:i A') }}
@endif

@if(!empty($task->description))
> **Description:**  
> {{ $task->description }}
@endif

---

@component('mail::button', ['url' => url('/teamlead/tasks/'.$task->id)])
View Task Details
@endcomponent

Please review this task and begin work as soon as possible.  
If you have any questions, contact **{{ $manager->name }}** directly.

Best Regards,  
**Project Management System**
@endcomponent
