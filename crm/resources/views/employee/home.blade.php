@extends('layout.app')

@section('content')
<div class="container">
 <div class="row justify-content-center">
    <div class="col-md-10">
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</div>

    <div class="row justify-content-center">
        <h2> 👋 Welcome, {{ Auth::guard('employee')->user()->name }}</h2>
    </div>
</div>
   
@endsection
