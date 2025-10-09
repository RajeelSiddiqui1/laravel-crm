@component('mail::message')
# Hello {{ $employee->name }},

You have been **assigned a new task** by your team lead.

**Task Name:** {{ $task->name }}  
**Description:** {{ $task->description ?? 'No description provided.' }}  
**Deadline:** {{ $task->deadline ?? 'Not specified' }}

@component('mail::button', ['url' => route('employee.tasks.show', $task->id)])
View Task
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
