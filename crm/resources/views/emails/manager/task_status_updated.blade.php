@component('mail::message')
# Hello {{ $recipient->name ?? 'User' }},

The status of a task has been updated.

### Task Details:
- **Task ID:** {{ $task->id }}
- **Task Title:** {{ $task->title ?? 'N/A' }}
- **Updated Status:** {{ ucfirst(str_replace('_', ' ', $status)) }}
- **Updated On:** {{ now()->format('d M, Y h:i A') }}

Please check your dashboard for more details.

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
View Task
@endcomponent

Thanks,  
**{{ config('app.name') }} Team**
@endcomponent
