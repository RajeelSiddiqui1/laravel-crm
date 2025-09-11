@extends('layout.app')

@section('content')
    <div class="container">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <div class="table-responsive">
            <table class="table table-bordered table-transparent">
                <thead class="bg-transparent">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Task Type</th>
                        <th scope="col">Leads</th>
                        <th scope="col">Start</th>
                        <th scope="col">End</th>
                        <th scope="col">Employee Status</th>
                        <th scope="col">Team Lead Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subtasks as $index => $subtask)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subtask->title }}</td>
                            <td>
                                @php
                                    $description = $subtask->description;
                                    $wordCount = str_word_count($description);
                                    $shortDescription = implode(' ', array_slice(explode(' ', $description), 0, 20));
                                @endphp
                                @if ($wordCount > 20)
                                    <span class="description-short">{{ $shortDescription }}...</span>
                                    <span class="description-full d-none">{{ $description }}</span>
                                    <a href="#" class="toggle-description text-primary">Show More</a>
                                @else
                                    {{ $description }}
                                @endif
                            </td>
                            <td>{{ $subtask->task_type ?? 'N/A' }}</td>
                            <td>{{ $subtask->lead }}</td>
                            <td>
                                @if ($subtask->start_date)
                                    {{ \Carbon\Carbon::parse($subtask->start_date)->format('Y-m-d') }}
                                    @if ($subtask->start_time)
                                        {{ \Carbon\Carbon::parse($subtask->start_time)->format('H:i') }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($subtask->end_date)
                                    {{ \Carbon\Carbon::parse($subtask->end_date)->format('Y-m-d') }}
                                    @if ($subtask->end_time)
                                        {{ \Carbon\Carbon::parse($subtask->end_time)->format('H:i') }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('employee.subtask_status_update', $subtask->id) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="employee_status" class="form-select form-select-sm bg-dark text-white border-secondary" onchange="this.form.submit()">
                                        <option value="pending" {{ $subtask->employee_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $subtask->employee_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                     
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span class="badge 
                                    @if ($subtask->teamlead_status == 'pending') bg-secondary
                                    @elseif ($subtask->teamlead_status == 'completed') bg-success
                                    @elseif ($subtask->teamlead_status == 'late') bg-warning
                                    @elseif ($subtask->teamlead_status == 'reject') bg-danger
                                    @else bg-light text-dark @endif">
                                    {{ ucfirst(str_replace('_', ' ', $subtask->teamlead_status ?? 'unknown')) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('employee.subtask.view', $subtask->id) }}" class="btn btn-sm btn-primary">
                                    Info
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-white">No subtasks assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .container {
            /* background-color: rgba(0, 0, 0, 0.7); */
            padding: 20px;
            border-radius: 12px;
            max-width: 1200px;
            backdrop-filter: blur(10px);
        }
        .table-transparent {
            background-color: transparent;
            color: #fff;
        }
        .table-transparent th,
        .table-transparent td {
            border-color: rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.05);
        }
        .bg-transparent {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
            border-radius: 0.25rem;
        }
        .btn-primary {
            background-color: rgba(0, 123, 255, 0.8);
            border-color: rgba(0, 123, 255, 0.8);
        }
        .btn-primary:hover {
            background-color: rgba(0, 86, 179, 0.8);
            border-color: rgba(0, 64, 133, 0.8);
        }
        .form-select {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }
        .form-select:focus {
            background-color: rgba(0, 0, 0, 0.9);
            border-color: rgba(0, 123, 255, 0.8);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .toggle-description {
            font-size: 0.9em;
            color: #0d6efd;
        }
        .description-full,
        .description-short {
            display: inline;
        }
        .d-none {
            display: none;
        }
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85em;
            }
            .table th,
            .table td {
                padding: 0.5rem;
            }
            .btn-sm {
                font-size: 0.8em;
                padding: 0.3rem 0.6rem;
            }
            .form-select-sm {
                font-size: 0.8em;
                padding: 0.3rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.toggle-description');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const short = this.parentElement.querySelector('.description-short');
                    const full = this.parentElement.querySelector('.description-full');
                    if (short.classList.contains('d-none')) {
                        short.classList.remove('d-none');
                        full.classList.add('d-none');
                        this.textContent = 'Show More';
                    } else {
                        short.classList.add('d-none');
                        full.classList.remove('d-none');
                        this.textContent = 'Show Less';
                    }
                });
            });

            const forms = document.querySelectorAll('.status-form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to update the status?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection