
@extends('layout.app')

@section('content')
    <div class="container">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-white">My Assigned Subtasks</h3>
            <a href="{{ route('employee.subtask.create') }}" class="btn btn-success">Create Subtask</a>
        </div> --}}

        <div class="table-responsive">
            <table class="table table-bordered table-dark">
                <thead class="bg-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Task Type</th>
                        <th scope="col">Leads</th>
                        <th scope="col">Start</th>
                        <th scope="col">End</th>
                        <th scope="col">Status</th>
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
                                <span class="badge 
                                    @if ($subtask->employee_status == 'pending') bg-secondary
                                    @elseif ($subtask->employee_status == 'in_progress') bg-warning
                                    @elseif ($subtask->employee_status == 'completed') bg-success
                                    @elseif ($subtask->employee_status == 'reject') bg-danger
                                    @elseif ($subtask->employee_status == 'late') bg-dark
                                    @else bg-light text-dark @endif">
                                    {{ ucfirst(str_replace('_', ' ', $subtask->employee_status ?? 'unknown')) }}
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
                            <td colspan="9" class="text-center">No subtasks assigned.</td>
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
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
            max-width: 1200px;
        }
        .table-dark {
            background-color: #2c2c2c;
            color: #fff;
        }
        .table-dark th,
        .table-dark td {
            border-color: #444;
        }
        .table-dark thead th {
            background-color: #1a1a1a;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .toggle-description {
            font-size: 0.9em;
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
        });
    </script>
@endsection
