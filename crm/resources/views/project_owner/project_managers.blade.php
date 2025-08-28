@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2>Project Managers</h2>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        {{-- <th scope="col">Departments</th> --}}
                                        <th scope="col">Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($managers as $manager)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $manager->name }}</td>
                                            <td>{{ $manager->email }}</td>
                                            <td>{{ $manager->phone }}</td>
                                           <td>
                @if (!empty($manager->departments_list))
                    @foreach ($manager->departments_list as $dept)
                        <span class="badge bg-info">{{ $dept }}</span>
                    @endforeach
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </td>
              <td>
                                <img src="{{ $manager->image ? asset('images/project_managers/' . $manager->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                    width="50" height="50" style="border-radius: 50%"
                                    alt="Manager\">
                            </td>

                                        </tr>
                                    @endforeach

                                    @if ($managers->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No Project Manager Found</td>
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
