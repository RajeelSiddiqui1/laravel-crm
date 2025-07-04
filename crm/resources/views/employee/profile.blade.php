@extends('layout.app')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4 col-md-5">
            <div class="card profile-card shadow-sm border-0 h-100 animate__animated animate__fadeIn">
                <div class="card-body pt-4 text-center">
                    <img src="{{ $employee->image ? asset('images/employees/' . $employee->image) : 'https://avatar.iran.liara.run/public/28' }}"
                         alt="profile-image" class="profile rounded-circle border border-3 border-white shadow mb-3"
                         style="width: 120px; height: 120px; object-fit: cover;">
                    <h5 class="card-title mb-2 fw-bold">{{ $employee->name }}</h5>
                    <p class="card-text text-muted mb-3">{{ $employee->email }}</p>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Update Profile</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">Profile Image</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max file size: 2MB. Accepted formats: JPG, PNG.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection