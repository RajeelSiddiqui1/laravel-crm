@extends('layout.app')


@section('content')
    <div class="container">

         <div class="row justify-content-center">
            <div class="col-md-10">
                @if (Session::has('error'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-3 justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-center">Project Manager Resgister</div>
                        <hr>
                        <form method="POST" action="{{ route('project_manager.register.post') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="input-1">Name</label>
                                <input type="text" name="name" class="form-control" id="input-1" placeholder="Enter Your Name"
                                @error('name')
                                    value="{{ old('name') }}"
                                    aria-describedby="nameHelp"
                                    @else
                                    value="{{ old('name') }}"
                                @enderror
                                >
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="input-2">Email</label>
                                <input type="text" name="email" class="form-control" id="input-2" placeholder="Enter Your Email Address"
                                @error('email')
                                    value="{{ old('email') }}"
                                    aria-describedby="emailHelp"
                                    @else
                                    value="{{ old('email') }}"
                                @enderror
                                >
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="input-3">Phone</label>
                                <input type="text" name="phone" class="form-control" id="input-3" placeholder="Enter Your Mobile Number"
                                @error('phone')
                                    value="{{ old('phone') }}"
                                    aria-describedby="phoneHelp"
                                    @else
                                    value="{{ old('phone') }}"
                                @enderror
                                >
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="input-4">Password</label>
                                <input type="text" name="password" class="form-control" id="input-4" placeholder="Enter Password"
                                @error('password')
                                    value="{{ old('password') }}"
                                    aria-describedby="passwordHelp"
                                    @else
                                    value="{{ old('password') }}"
                                    @enderror
                                >
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="input-5">Confirm Password</label>
                                <input type="text" name="password_confirmation" class="form-control" id="input-5" placeholder="Confirm Password"
                                @error('password_confirmation')
                                    value="{{ old('password_confirmation') }}"
                                    aria-describedby="passwordConfirmationHelp"
                                    @else
                                    value="{{ old('password_confirmation') }}"
                                @enderror
                                >
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="input-3">Image</label>
                                <input type="file" name="image" class="form-control" id="input-3" placeholder="Enter Your Image"
                                @error('image')
                                    value="{{ old('image') }}"
                                    aria-describedby="imageHelp"
                                    @else
                                    value="{{ old('image') }}"
                                @enderror
                                >
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="form-group">
                            <label for="department_id">Select Department</label>
                            <select name="department_id" id="department_id" class="form-control" >
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                            

                            <div class="form-group">
                                <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection