@component('mail::message')
# Hello {{ $teamLead->name }},

You have been assigned a new **Operation Task**.

### Task Details:
- **Task ID:** {{ $sharedTask->id }}
- **Task Title:** {{ $sharedTask->title ?? 'N/A' }}
- **Assigned Date:** {{ $sharedTask->updated_at->format('d M, Y h:i A') }}

Please log in to your dashboard to view the full details and take necessary actions.

@component('mail::button', ['url' => url('/teamlead/tasks/' . $sharedTask->id)])
View Task
@endcomponent

Thanks,  
**{{ config('app.name') }} Team**
@endcomponent
