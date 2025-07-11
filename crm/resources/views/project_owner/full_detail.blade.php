@extends('layout.app')

@section('content')
<div class="container mt-5 text-white">
    <h2 class="mb-4">Full Task Details</h2>

    <div class="card bg-dark p-4">
        <h4 class="mb-3">Client: {{ $task->client_name }}</h4>
        <p>Description: {{ $task->description }}</p>
        <p>Email: {{ $task->client_email }}</p>
        <p>Contact: {{ $task->client_contact }}</p>

        <hr>

        <h5>Team Lead:</h5>
        <p>Name: {{ $task->teamLead->name ?? 'N/A' }}</p>
        <p>Email: {{ $task->teamLead->email ?? 'N/A' }}</p>

        <h5>Employee:</h5>
        <p>Name: {{ $task->employee->name ?? 'N/A' }}</p>
        <p>Email: {{ $task->employee->email ?? 'N/A' }}</p>

        <a href="{{ route('project_owner.task') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</div>
@endsection
