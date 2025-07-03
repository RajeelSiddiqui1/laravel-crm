@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row mt-3 justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-center">Department Edit</div>
                        <hr>
                        <form method="POST" action="{{ route('department.edit.post', $department->id) }}" >
                            @csrf

                            <div class="form-group">
                                <label for="input-2">Name</label>
                                <input type="text" name="name" class="form-control" id="input-2"
                                    placeholder="Enter Your Department Name"
                                    @error('name')
                                    value="{{ old('name') }}"
                                    @else
                                    value="{{ $department->name }}"
                                @enderror>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>





                            <div class="form-group">
                                <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i>
                                    Update</button>
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    </div>
@endsection
