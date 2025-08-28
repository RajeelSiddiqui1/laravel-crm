@extends('layout.app')

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
                <h2 class="text-center mb-4">Team Leads</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('project_manager.create_teamlead_view') }}" class="btn btn-primary mb-3">Create Task</a>
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
                    @forelse ($teamleads as $team)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $team->name }}</td>
                            <td>{{ $team->email }}</td>
                            <td>{{ $team->phone }}</td>
                            <td>{{ $team->department->name }}</td>
                            <td>
                                <img src="{{ $team->image ? asset('images/team_leads/' . $team->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                    style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;"
                                    alt="Team Lead">
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No TeamLead Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
