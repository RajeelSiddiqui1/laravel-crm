@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2>Employees</h2>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Departments</th>
                                        <th scope="col">Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>
                                                 @if ($employee->department)
                                                    <span class="badge badge-info">{{ $employee->department->name }}</span>
                                                @else
                                                    <span class="text-muted">No Team Lead</span>
                                                @endif
                                               
                                            </td>
                                            <td>
                                                <img src="{{ asset('images/employees/' . $employee->image) }}"
                                                    class="img-circle" alt="user avatar" width="50" height="50"
                                                    style="border-radius: 50%">
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($employees->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No Project employee Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
