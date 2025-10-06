@extends('layout.app')
<style>
    select.form-control {
    background-color: transparent; /* solid dark */
    color: #fff !important; /* text white */
    border: 1px solid #444; /* border visible */
}

select.form-control option {
    background-color: transparent; /* dark background for options */
    color: #fff; /* white text for options */
}

</style>

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow rounded">
            <div class="card-body p-4">
                <div class="card-title text-center text-white mb-3">
                    <h2>Create TeamLead</h2>
                </div>
                <form method="POST" action="{{ route('project_manager.create_teamlead') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="text-white">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="text-white">Email</label>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="text-white">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    

                    <div class="form-group">
                        <label class="text-white">Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="departmentSelect" class="text-white">Select Department</label>
                        <select class="form-control" id="departmentSelect" name="department_id">
                            <option value="" disabled selected>Select a department</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-light px-5">Create</button>
                    </div>

                   
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
