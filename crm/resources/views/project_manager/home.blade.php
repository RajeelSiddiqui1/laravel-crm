@extends('layout.app')

@section('content')
<div class="container">
 <div class="row justify-content-center">
    <div class="col-md-10">
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
    </div>
</div>

    <div class="row justify-content-center">
        <h2> 👋 Welcome, {{ Auth::guard('project_manager')->user()->name }}</h2>
    </div>
</div>
   
@endsection
