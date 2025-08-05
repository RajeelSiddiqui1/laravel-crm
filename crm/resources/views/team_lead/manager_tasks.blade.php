 @php use Illuminate\Support\Str; @endphp

 @extends('layout.app')

 @section('styles')
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
     <style>
         :root {
             --body-bg: #0d0d11;
             --table-bg: #1a1b26;
             --accent: #7b68ee;
             --text: #f5f5f5;
         }

         body {
             background: var(--body-bg);
             color: var(--text);
             font-family: 'Inter', sans-serif;
         }

         .table {
             background: var(--table-bg);
             border: none;
             border-radius: .75rem;
             overflow: hidden;
         }

         .table thead {
             background: linear-gradient(90deg, var(--accent), #8a5cf5);
             color: #fff;
             font-weight: 600;
         }

         .table th,
         .table td {
             vertical-align: middle;
             padding: 0.75rem;
             text-align: center;
         }

         .btn-primary {
             background: var(--accent);
             border: none;
             border-radius: 0.5rem;
         }

         .btn-primary:hover {
             background: #5a4fcf;
         }

         .btn-success,
         .btn-warning,
         .btn-danger {
             border-radius: 0.5rem;
         }

         .form-control,
         .form-select {
             background: #252837;
             border: 1px solid #3a3c4f;
             color: var(--text);
             border-radius: 0.5rem;
         }

         .form-control:focus,
         .form-select:focus {
             background: #252837;
             border-color: var(--accent);
             box-shadow: 0 0 0 .2rem rgba(123, 104, 238, .25);
         }

         .badge {
             font-size: 0.85rem;
             padding: 0.5rem 1rem;
             border-radius: 50px;
         }

         .badge-info {
             background: #17a2b8;
         }

         .badge-success {
             background: #28a745;
         }

         .badge-warning {
             background: #ffc107;
         }

         .badge-secondary {
             background: #6c757d;
         }

         .attachment-grid {
             display: grid;
             grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
             gap: .5rem;
         }

         .attachment-item img,
         .attachment-item video {
             height: 100px;
             width: 100%;
             object-fit: cover;
             border-radius: .5rem;
         }
     </style>
 @endsection

 @section('content')
     <div class="container mt-4">
         <div class="row justify-content-center">
             <div class="col-md-8">
                 <h2 class="text-center mb-4 fw-bold">My Tasks Dashboard</h2>
             </div>
         </div>



         <div class="table-responsive">
             <table class="table table-bordered table-hover">
                 <thead>
                     <tr class="text-center align-middle">
                         <th>#</th>
                         <th>Department</th>
                         <th>Team Lead</th>
                         <th>Manager Status</th>
                         <th>Shared</th>
                         @if ($tasks->pluck('account')->filter()->isNotEmpty())
                             <th>Client Name</th>
                             <th>Email</th>
                             <th>Due Date</th>
                             <th>Business</th>
                             <th>Priority</th>
                             <th>Attachments</th>
                         @else
                             <th>Account Info</th>
                         @endif
                         <th>View</th>
                         <th>Actions</th>
                         <th>Manager</th>
                         <th>Assigned Employees</th>
                         <th>Subtask</th>
                         <th>Subtask View</th>
                         <th>Group Chat</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($tasks as $task)
                         <tr class="align-middle text-center">
                             <td>{{ $loop->iteration }}</td>
                             <!-- Department -->
                             <td>
                                 @if ($task->department && $task->department->name === 'Call operator')
                                     <span class="badge badge-info">Call Operator</span>
                                 @else
                                     {{ $task->department->name ?? 'No Department' }}
                                 @endif
                             </td>
                             <!-- Team Lead -->
                             <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                             <!-- Team Lead Status -->
                             <td>
                                 <span
                                     class="badge badge-{{ $task->status2 === 'approved'
                                         ? 'success'
                                         : ($task->status2 === 'pending'
                                             ? 'secondary'
                                             : ($task->status2 === 'rejected'
                                                 ? 'danger'
                                                 : ($task->status2 === 'late'
                                                     ? 'warning'
                                                     : 'dark'))) }}">
                                     {{ ucfirst($task->status2) }}
                                 </span>

                             </td>
                             <!-- Your Status -->

                             <!-- Shared Status -->
                             <td>
                                 <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                     {{ $task->is_shared ? 'Yes' : 'No' }}
                                 </span>
                             </td>
                             <!-- Account Info -->
                             @if ($tasks->pluck('account')->filter()->isNotEmpty())
                                 <td>{{ $task->account ? $task->account->clientname : 'N/A' }}</td>
                                 <td>{{ $task->account ? $task->account->email : 'N/A' }}</td>
                                 <td>{{ $task->account ? $task->account->due_date : 'N/A' }}</td>
                                 <td>{{ $task->account ? $task->account->nature_of_business : 'N/A' }}</td>
                                 <td>
                                     @if ($task->account)
                                         <span
                                             class="badge badge-{{ $task->account->priority == 'high' ? 'danger' : ($task->account->priority == 'medium' ? 'warning' : 'success') }}">
                                             {{ ucfirst($task->account->priority) }}
                                         </span>
                                     @else
                                         N/A
                                     @endif
                                 </td>


                                 <td>
                                     @if ($task->account && !empty($task->account->attachments))
                                         <div class="attachment-grid">
                                             @foreach ((array) $task->account->attachments as $url)
                                                 @php
                                                     $fileUrl = Str::startsWith($url, ['http://', 'https://'])
                                                         ? $url
                                                         : asset('storage/' . $url);
                                                     $ext = strtolower(
                                                         pathinfo(
                                                             parse_url($fileUrl, PHP_URL_PATH),
                                                             PATHINFO_EXTENSION,
                                                         ),
                                                     );
                                                     $fileName = basename($url);
                                                 @endphp

                                                 <div class="attachment-item text-center">
                                                     @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                         <a href="{{ $fileUrl }}" target="_blank">
                                                             <img src="{{ $fileUrl }}" alt="Image"
                                                                 style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;">
                                                         </a>
                                                     @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                         <video src="{{ $fileUrl }}" controls
                                                             style="width: 100%; height: 100px; border-radius: 8px;"></video>
                                                     @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                         <audio controls style="width: 100%;">
                                                             <source src="{{ $fileUrl }}"
                                                                 type="audio/{{ $ext }}">
                                                         </audio>
                                                     @elseif (in_array($ext, ['pdf']))
                                                         <a href="{{ $fileUrl }}" target="_blank"
                                                             title="{{ $fileName }}">
                                                             <img src="https://img.icons8.com/color/48/000000/pdf.png"
                                                                 alt="PDF" style="height: 48px;">
                                                             <div style="font-size: 12px; color: #f5f5f5;">
                                                                 {{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                         </a>
                                                     @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                         <a href="{{ $fileUrl }}" target="_blank"
                                                             title="{{ $fileName }}">
                                                             <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                                 alt="Excel" style="height: 48px;">
                                                             <div style="font-size: 12px; color: #f5f5f5;">
                                                                 {{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                         </a>
                                                     @elseif (in_array($ext, ['doc', 'docx']))
                                                         <a href="{{ $fileUrl }}" target="_blank"
                                                             title="{{ $fileName }}">
                                                             <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                                 alt="Word" style="height: 48px;">
                                                             <div style="font-size: 12px; color: #f5f5f5;">
                                                                 {{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                         </a>
                                                     @else
                                                         <a href="{{ $fileUrl }}" target="_blank"
                                                             title="{{ $fileName }}">
                                                             <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                                 alt="File" style="height: 48px;">
                                                             <div style="font-size: 12px; color: #f5f5f5;">
                                                                 {{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                         </a>
                                                     @endif
                                                 </div>
                                             @endforeach
                                         </div>
                                     @else
                                         <span class="text-muted">No Attachments</span>
                                     @endif
                                 </td>
                             @else
                                 <td>
                                     @if ($task->account)
                                         <div><strong>Client:</strong> {{ $task->account->clientname }}</div>
                                         <div><strong>Email:</strong> {{ $task->account->email }}</div>
                                         <div><strong>Due:</strong> {{ $task->account->due_date }}</div>
                                         <div><strong>Business:</strong> {{ $task->account->nature_of_business }}</div>
                                         <div><strong>Priority:</strong> {{ ucfirst($task->account->priority) }}</div>
                                         @if ($task->account && !empty($task->account->attachments))
                                             <div class="attachment-grid">
                                                 @foreach ((array) $task->account->attachments as $url)
                                                     @php $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                                                     <div class="attachment-item">
                                                         @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                             <img src="{{ asset('storage/' . $url) }}" alt="Attachment">
                                                         @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                             <video controls src="{{ asset('storage/' . $url) }}"></video>
                                                         @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                             <audio controls src="{{ asset('storage/' . $url) }}"></audio>
                                                         @else
                                                             <a href="{{ asset('storage/' . $url) }}" target="_blank"
                                                                 class="btn btn-outline-light btn-sm w-100">View</a>
                                                         @endif
                                                     </div>
                                                 @endforeach
                                             </div>
                                         @else
                                             <span class="text-muted">No Attachments</span>
                                         @endif
                                     @else
                                         <span class="text-muted">No Account Info</span>
                                     @endif
                                 </td>
                             @endif
                             <!-- View -->
                             <td>
                                 <a href="{{ route('project_manager.my_task_detail', $task->id) }}"
                                     class="btn btn-sm btn-success">View</a>
                             </td>
                             <!-- Chat -->

                             
                             <td>
                                 <form method="POST" action="{{ route('team_lead.tasks.update_status', $task->id) }}">
                                     @csrf
                                     @method('PUT')
                                     <select name="status" class="form-select form-select-sm" onchange="this.form.submit()"
                                         style="min-width: 120px;">
                                         <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                             Pending
                                         </option>
                                         <option value="in_progress"
                                             {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                         <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                             Completed</option>
                                         <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>
                                             Cancelled</option>
                                     </select>
                                 </form>
                             </td>
                             <td>{{ $task->projectManager->name ?? 'N/A' }}</td>

                             @php
                                 $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                                 $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                                 $unassignedEmployees = $employees
                                     ->whereNotIn('id', $assignedEmployeeIds)
                                     ->where('department_id', $task->department_id);
                             @endphp
                             <td>
                                 @if ($assignedEmployees->isNotEmpty())
                                     @foreach ($assignedEmployees as $employee)
                                         <div class="employee-name">{{ $employee->name }}</div>
                                     @endforeach
                                 @else
                                     <div class="employee-name text-muted">No employees assigned</div>
                                 @endif
                                 @if ($unassignedEmployees->isNotEmpty())
                                     <form method="POST"
                                         action="{{ route('team_lead.tasks.assign_employees', $task->id) }}">
                                         @csrf
                                         <div class="dropdown mt-2">
                                             <button class="btn btn-sm dropdown-toggle" type="button"
                                                 id="dropdown-{{ $task->id }}" data-bs-toggle="dropdown"
                                                 aria-expanded="false">
                                                 Assign Employees
                                             </button>
                                             <ul class="dropdown-menu checkbox-wrapper"
                                                 aria-labelledby="dropdown-{{ $task->id }}">
                                                 @foreach ($unassignedEmployees as $employee)
                                                     <li>
                                                         <div class="form-check">
                                                             <input class="form-check-input" type="checkbox"
                                                                 name="employee_id[]" value="{{ $employee->id }}"
                                                                 id="emp-{{ $task->id }}-{{ $employee->id }}">
                                                             <label class="form-check-label"
                                                                 for="emp-{{ $task->id }}-{{ $employee->id }}">{{ $employee->name }}</label>
                                                         </div>
                                                     </li>
                                                 @endforeach
                                                 <li>
                                                     <hr class="dropdown-divider">
                                                 </li>
                                                 <li><button type="submit"
                                                         class="btn btn-sm btn-success w-100">Assign</button></li>
                                             </ul>
                                         </div>
                                     </form>
                                 @endif
                             </td>

                             <td>
                                 <a href="{{ route('team_lead.subtask.create', $task->id) }}"
                                     class="btn btn-sm btn-warning mb-1">Subtask</a>

                             </td>

                             <td>
                                 <a href="{{ route('team_lead.subtask.list', $task->id) }}"
                                     class="btn btn-sm btn-info">Subtask Assign</a>
                             </td>
                             <td>
                                 <a href="{{ route('chat.group', $task->id) }}"
                                     class="btn btn-outline-primary btn-sm">Group
                                     Chat</a>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             @if ($tasks->pluck('account')->filter()->isNotEmpty())
                                 <td colspan="14" class="text-center text-muted">No Tasks Found</td>
                             @else
                                 <td colspan="8" class="text-center text-muted">No Tasks Found</td>
                             @endif
                         </tr>
                     @endforelse
                 </tbody>
             </table>
         </div>
     </div>
 @endsection
