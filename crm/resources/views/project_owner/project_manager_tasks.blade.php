@extends('layout.app')

@section('content')
<div class="container mt-4 text-white">
    <h2 class="mb-4">All Assigned Owner Tasks</h2>

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

    <div class="row">
        <div class="col-md-6">
            <input type="text" value="{{$tasks->account_t1_id->clientname}}" class="form-control" readonly>
        </div>
    </div>
</div>
@endsection
