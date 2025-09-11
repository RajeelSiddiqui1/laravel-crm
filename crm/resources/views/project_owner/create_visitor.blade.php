@extends('layout.app')
<style>
    select.form-control {
        background-color: transparent;
        /* solid dark */
        color: #fff !important;
        /* text white */
        border: 1px solid #444;
        /* border visible */
    }

    select.form-control option {
        background-color: transparent;
        /* dark background for options */
        color: #fff;
        /* white text for options */
    }
</style>
@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow rounded">
                <div class="card-body p-4">
                    <div class="card-title text-center text-white mb-3">
                        <h2>Create Visitor</h2>
                    </div>
                    <form method="POST" action="{{ route('project_owner.create.visitor') }}" enctype="multipart/form-data">
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
                            <label class="text-white">Select Department(s)</label>
                            <div>
                                @foreach ($departments as $dept)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="department_id[]"
                                            value="{{ $dept->id }}" id="dept{{ $dept->id }}"
                                            @if (is_array(old('department_id')) && in_array($dept->id, old('department_id'))) checked @endif>
                                        <label class="form-check-label text-white" for="dept{{ $dept->id }}">
                                            {{ $dept->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
