@component('mail::message')
# 📝 Task Updated Notification

Hello Project Manager,

A task assigned to you has been **updated** by the owner.

---

### 🔹 Task Details
**Client Name:** {{ $task->clientname }}  
**Corporation Name:** {{ $task->corporation_name ?? 'N/A' }}  
**Due Date:** {{ $task->due_date ?? 'N/A' }}  
**Priority:** {{ ucfirst($task->priority ?? 'normal') }}  
**Status:** {{ ucfirst($task->manager_status ?? 'pending') }}  

@if(!empty($task->nature_of_business))
**Nature of Business:** {{ $task->nature_of_business }}
@endif

---

@component('mail::button', ['url' => url('/project-manager/tasks/'.$task->id)])
View Task
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
