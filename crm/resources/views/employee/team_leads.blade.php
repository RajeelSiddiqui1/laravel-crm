@extends('layout.app')

@section('content')
<style>
    body {
        background: #121212;
    }

    .employee-card {
        backdrop-filter: blur(2px);
        background-color: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        padding: 25px;
        margin-bottom: 40px;
        transition: all 0.3s ease-in-out;
        color: #fff;
    }

    .employee-card:hover {
        transform: scale(1.05);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6);
    }

    .employee-image {
        width: 100%;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .employee-details h4,
    .employee-details h6 {
        margin: 5px 0;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
    }

    @media (max-width: 768px) {
        .employee-image {
            height: 200px;
        }
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        @foreach ($teamLeads as $team)
            <div class="col-md-4 px-3">
                <div class="employee-card text-center">
                    <img src="{{ asset('images/team_leads/' . $team->image) }}" alt="Team Lead Image" class="employee-image">
                    <div class="employee-details">
                        <h4>{{ $team->name }}</h4>
                        <h6>{{ $team->email }}</h6>
                        <h6>{{ $team->department->name }}</h6>
                    </div>
                    <a href="{{ route('employee.message.teamlead', $team->id) }}" class="btn btn-primary mt-3">
                        <i class="fas fa-envelope mr-2"></i> Message
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
