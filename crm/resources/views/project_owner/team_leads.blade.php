@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2>Team Leads</h2>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Basic Table</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamLeads as $lead)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->email }}</td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>
                                                @if ($lead->department)
                                                    <span class="badge badge-info">{{ $lead->department->name }}</span>
                                                @else
                                                    <span class="text-muted">No Team Lead</span>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ asset('images/team_leads/' . $lead->image) }}"
                                                    alt="team lead image"
                                                    class="img-circle"
                                                    width="50"
                                                    height="50"
                                                    style="border-radius: 50%">
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($teamLeads->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No Team Leads Found</td>
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
