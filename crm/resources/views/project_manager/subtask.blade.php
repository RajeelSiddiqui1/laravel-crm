@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h3 class="text-white mb-4">Subtasks Grouped by Employee</h3>

        @php
            $grouped = $subtasks->groupBy(function ($subtask) {
                return $subtask->employee->name ?? 'Unknown';
            });

            $statusColors = [
                'pending' => 'warning',
                'in_progress' => 'primary',
                'approved' => 'success',
                'rejected' => 'danger',
                'late' => 'secondary',
            ];
        @endphp

        <div class="mb-4 d-flex flex-wrap gap-2">
            <button class="btn btn-light filter-btn" data-employee="all">All Employees</button>
            @foreach ($grouped as $employeeName => $subtasks)
                <button class="btn btn-light mx-1 filter-btn" data-employee="{{ Str::slug($employeeName) }}">
                    {{ $employeeName }}
                </button>
            @endforeach
        </div>

        @forelse($grouped as $employeeName => $subtasks)
            @php
                $employeeSlug = Str::slug($employeeName);
                $statusCounts = $subtasks->groupBy('status')->map->count();
            @endphp

            <div class="card mb-4 employee-group" data-employee="{{ $employeeSlug }}">
                <div class="card-header text-white d-flex justify-content-between align-items-center">
                    <span>{{ $employeeName }}</span>
                    <div>
                        <select class="form-control form-control-sm status-filter" data-employee="{{ $employeeSlug }}">
                            <option value="all">All Statuses</option>
                            @foreach ($statusColors as $status => $color)
                                <option value="{{ $status }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }} ({{ $statusCounts[$status] ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 table-dark">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Subtask</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subtasks as $subtask)
                                    @php
                                        $rowClass = match ($subtask->status) {
                                            'pending' => 'table-warning',
                                            'in_progress' => 'table-primary',
                                            'approved' => 'table-success',
                                            'rejected' => 'table-danger',
                                            'late' => 'table-secondary',
                                            default => '',
                                        };
                                    @endphp
                                    <tr class="subtask-row {{ $rowClass }}" data-status="{{ $subtask->status }}"
                                        data-employee="{{ $employeeSlug }}">
                                        <td>{{ $subtask->employee->name ?? 'Unknown' }}</td>
                                        <td>{{ $subtask->title }}</td>
                                        <td>{{ $subtask->start_date }}</td>
                                        <td>{{ $subtask->end_date }}</td>
                                        <td>{{ $subtask->start_time }}</td>
                                        <td>{{ $subtask->end_time }}</td>
                                        <td>
                                            <span class="badge bg-{{ $statusColors[$subtask->status] ?? 'light' }}">
                                                {{ ucfirst($subtask->status) }}
                                            </span>
                                        </td>

                                      <td>
                                          <a href="{{ route('project_manager.subtask_detail', $subtask->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        
                                      </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-white">No subtasks found.</p>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const employeeButtons = document.querySelectorAll('.filter-btn');
            const groups = document.querySelectorAll('.employee-group');
            const statusFilters = document.querySelectorAll('.status-filter');

            let selectedEmployee = 'all';

            employeeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedEmployee = btn.getAttribute('data-employee');
                    employeeButtons.forEach(b => b.classList.remove('btn-light'));
                    btn.classList.add('btn-light');
                    filterGroups();
                });
            });

            statusFilters.forEach(select => {
                select.addEventListener('change', () => {
                    const employee = select.getAttribute('data-employee');
                    const status = select.value;

                    document.querySelectorAll(
                        `.employee-group[data-employee="${employee}"] .subtask-row`).forEach(
                        row => {
                            const rowStatus = row.getAttribute('data-status');
                            row.style.display = (status === 'all' || status === rowStatus) ?
                                '' : 'none';
                        });
                });
            });

            function filterGroups() {
                groups.forEach(group => {
                    const emp = group.getAttribute('data-employee');
                    group.style.display = (selectedEmployee === 'all' || selectedEmployee === emp) ? '' :
                        'none';
                });
            }

            filterGroups();
        });
    </script>
@endsection
