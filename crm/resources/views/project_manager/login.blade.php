@extends('layout.app')


@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (Session::has('success'))
                    <div class="alert alert-sucess alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
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
                        <div class="card-title text-center">Project Manager Login</div>
                        <hr>
                        <form method="POST" action="{{ route('project_manager.register.post') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="input-2">Email</label>
                                <input type="text" name="email" class="form-control" id="input-2"
                                    placeholder="Enter Your Email Address"
                                    @error('email')
                                    value="{{ old('email') }}"
                                    aria-describedby="emailHelp"
                                    @else
                                    value="{{ old('email') }}"
                                @enderror>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="input-4">Password</label>
                                <input type="text" name="password" class="form-control" id="input-4"
                                    placeholder="Enter Password"
                                    @error('password')
                                    value="{{ old('password') }}"
                                    aria-describedby="passwordHelp"
                                    @else
                                    value="{{ old('password') }}"
                                    @enderror>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>




                            <div class="form-group">
                                <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
