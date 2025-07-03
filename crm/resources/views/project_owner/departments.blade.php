@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2>Department Table</h2>

        </div>
        <div class="row justify-content-end">
            <div class="col-md-3 justify-content-end">
                <!-- You can add a button here for adding new departments -->
                <a href="{{ route('department.create') }}" class="btn btn-primary">Add Department</a>
            </div>
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
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $dp)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $dp->name }}</td>

                                            <td>
                                                <a href="{{ route('department.edit', $dp->id) }}"
                                                    class="btn btn-warning">Edit</a>

                                            <td>

                                                <form action="{{ route('department.delete', $dp->id) }}" method="post">

                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                    <!-- You can add a confirmation dialog here -->

                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--End Row-->
    </div>
@endsection
