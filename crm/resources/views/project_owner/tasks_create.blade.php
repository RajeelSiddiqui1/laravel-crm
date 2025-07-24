@extends('layout.app')

<style>
    .card-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding: 1.5rem;
        background-color: transparent;
        border-radius: 0.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
        position: relative;
    }

    .form-control {
        height: 2.5rem;
        font-size: 0.95rem;
        border-radius: 0.25rem;
        background-color: #222;
        color: #fff;
        border: 1px solid #555;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #007bff;
        background-color: #333;
        box-shadow: none;
        outline: none;
    }

    textarea.form-control {
        height: 6rem;
        resize: vertical;
    }

    .btn-primary {
        padding: 0.75rem 2rem;
        font-size: 0.95rem;
        background-color: #007bff;
        border: none;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        padding: 0.75rem 2rem;
        font-size: 0.95rem;
        background-color: #6c757d;
        border: none;
    }

    .alert {
        margin-bottom: 1rem;
        font-size: 0.9rem;
        border-radius: 0.25rem;
    }

    .text-danger {
        font-size: 0.85rem;
        position: absolute;
        bottom: -1.5rem;
        left: 0;
    }

    .card-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #fff;
    }

    .custom-select {
        border-radius: 0.25rem;
        padding: 0.5rem;
        border: 1px solid #555;
        background-color: #222;
        color: #fff;
        font-size: 0.95rem;
    }

    .custom-select:focus {
        border-color: #007bff;
        background-color: #333;
        color: #fff;
        outline: none;
        box-shadow: none;
    }

    .required::after {
        content: '*';
        color: #dc3545;
        margin-left: 0.25rem;
    }

    .loading::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .char-count {
        font-size: 0.8rem;
        color: #aaa;
        text-align: right;
        margin-top: 0.25rem;
    }
</style>

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (session('success_swal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: "{{ session('success_swal') }}",
                                icon: 'success',
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff'
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
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff'
                            });
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded">
                    <div class="card-body">
                        <h2 class="card-title text-center">Create Task</h2>
                        <form method="POST" action="{{ route('project_manager.tasks.post') }}" id="taskForm">
                            @csrf

                            <fieldset>
                                <legend class="text-white mb-3" style="font-size: 1.1rem;">Task Details</legend>
                                <div class="form-group">
                                    <label class="text-white required" for="name">Task Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                        aria-required="true" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white required" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" maxlength="500">{{ old('description') }}</textarea>
                                    <div class="char-count" id="descCount">0/500 characters</div>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white required" for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ old('start_date') }}" aria-required="true" required>
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white required" for="deadline">Deadline</label>
                                    <input type="date" name="deadline" id="deadline" class="form-control"
                                        value="{{ old('deadline') }}" aria-required="true" required>
                                    @error('deadline')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white required" for="priority">Priority</label>
                                    <select name="priority" id="priority" class="form-control custom-select" aria-required="true" required>
                                        <option value="">Select Priority</option>
                                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                                    </select>
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend class="text-white mb-3" style="font-size: 1.1rem;">Client Details</legend>
                                <div class="form-group">
                                    <label class="text-white" for="client_name">Client Name</label>
                                    <input type="text" name="client_name" id="client_name" class="form-control"
                                        value="{{ old('client_name') }}">
                                    @error('client_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white" for="client_email">Client Email</label>
                                    <input type="email" name="client_email" id="client_email" class="form-control"
                                        value="{{ old('client_email') }}">
                                    @error('client_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white" for="client_contact">Client Contact</label>
                                    <input type="text" name="client_contact" id="client_contact" class="form-control"
                                        value="{{ old('client_contact') }}" placeholder="e.g., +1234567890">
                                    @error('client_contact')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend class="text-white mb-3" style="font-size: 1.1rem;">Assignment</legend>
                                <div class="form-group">
                                    <label class="text-white required" for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control custom-select" aria-required="true" required>
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-white required" for="project_manager_id">Project Manager</label>
                                    <select name="project_manager_id" id="project_manager_id" class="form-control custom-select" aria-required="true" required>
                                        <option value="">Select Project Manager</option>
                                    </select>
                                    @error('project_manager_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </fieldset>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary">Create Task</button>
                                <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // AJAX for project managers
            $('#department_id').on('change', function() {
                let departmentId = $(this).val();
                let $projectManagerSelect = $('#project_manager_id');
                $projectManagerSelect.prop('disabled', true).addClass('loading');

                if (departmentId) {
                    $.ajax({
                        url: '/get-project-managers/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $projectManagerSelect.empty().append('<option value="">Select Project Manager</option>');
                            $.each(data, function(key, manager) {
                                $projectManagerSelect.append('<option value="' + manager.id + '">' + manager.name + '</option>');
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to load project managers.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff'
                            });
                        },
                        complete: function() {
                            $projectManagerSelect.prop('disabled', false).removeClass('loading');
                        }
                    });
                } else {
                    $projectManagerSelect.empty().append('<option value="">Select Project Manager</option>').prop('disabled', false).removeClass('loading');
                }
            });

            // Character counter for description
            $('#description').on('input', function() {
                let count = $(this).val().length;
                $('#descCount').text(`${count}/500 characters`);
            });

            // Real-time validation feedback
            $('#taskForm input, #taskForm select, #taskForm textarea').on('input change', function() {
                let $input = $(this);
                let $error = $input.next('.text-danger');
                if ($input[0].checkValidity()) {
                    $input.css('border-color', '#555');
                    if ($error.length) $error.remove();
                } else {
                    $input.css('border-color', '#dc3545');
                }
            });

            // Form submission validation
            $('#taskForm').on('submit', function(e) {
                let isValid = true;
                $(this).find('[required]').each(function() {
                    if (!this.checkValidity()) {
                        $(this).css('border-color', '#dc3545');
                        isValid = false;
                    }
                });
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please fill all required fields correctly.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        background: '#1a1a1a',
                        color: '#fff'
                    });
                }
            });
        });
    </script>
@endsection