@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #121217;
            --primary: #4f46e5;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #d1d5db;
            --border: #2d3748;
            --table-bg: rgba(31, 41, 55, 0.6);
            --hover-bg: rgba(75, 85, 99, 0.2);
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .table thead {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 1rem;
            text-align: center;
            border: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--hover-bg);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        h2.text-center {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        @if (session('success_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if (session('error_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal') }}",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Visitors</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('project_manager.create_teamlead_view') }}" class="btn btn-primary mb-3">Create TeamLead</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitors as $visitor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->email }}</td>
                            <td>{{ $visitor->phone }}</td>
                            <td>
                                @if ($visitor->department_ids)
                                    @php
                                        $departments = \App\Models\Department::whereIn(
                                            'id',
                                            json_decode($visitor->department_ids),
                                        )
                                            ->pluck('name')
                                            ->toArray();
                                    @endphp
                                    {{ implode(', ', $departments) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <img src="{{ $visitor->image ? asset('images/visitors/' . $visitor->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                    style="height:50px; width:50px; object-fit:cover; border-radius:8px;" alt="Visitor">
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No Visitor Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
