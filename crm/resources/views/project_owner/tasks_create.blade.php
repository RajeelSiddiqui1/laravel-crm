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

    .form-control,
    select.form-control {
        height: 2.5rem;
        font-size: 0.95rem;
        border-radius: 0.25rem;
        background-color: #222;
        color: #fff;
        border: 1px solid #555;
        transition: border-color 0.2s;
        width: 100%;
        padding: 0.5rem;
    }

    .form-control:focus,
    select.form-control:focus {
        border-color: #007bff;
        background-color: #333;
        box-shadow: none;
        outline: none;
    }

    .checkbox-group {
        max-height: 6rem;
        overflow-y: auto;
        padding: 0.5rem;
        background-color: #222;
        border: 1px solid #555;
        border-radius: 0.25rem;
    }

    .checkbox-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #fff;
    }

    .checkbox-group input[type="checkbox"] {
        margin-right: 0.5rem;
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
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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
                        <form method="POST" action="{{ route('project_owner.tasks.post') }}" id="taskForm">
                            @csrf

                            <fieldset>
                                <div class="form-group">
                                    <label class="text-white" for="client_name">Client Name</label>
                                    <input type="text" name="client_name" id="client_name" class="form-control"
                                        value="{{ old('client_name') }}">
                                    @error('client_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="text-white required" for="managers">Managers (Select Multiple)</label>
                                    <div class="checkbox-group">
                                        @foreach ($managers as $manager)
                                            <label style="display: block; margin-bottom: 0.5rem;">
                                                <input type="checkbox" name="managers[]" value="{{ $manager['id'] }}"
                                                    {{ in_array($manager['id'], old('managers', [])) ? 'checked' : '' }}>
                                                <strong>{{ $manager['name'] }}</strong>

                                                @if (!empty($manager['departments']))
                                                    <span style="color: #aaa; font-size: 0.85rem;">
                                                        — ({{ implode(', ', $manager['departments']) }})
                                                    </span>
                                                @else
                                                    <span style="color: #666; font-size: 0.85rem;">(No Departments)</span>
                                                @endif
                                            </label>
                                        @endforeach

                                    </div>
                                    @error('managers')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group text-center mt-3">
                                    <label class="text-white d-block">Record Audio (Admin)</label>
                                    <div id="recorderControls">
                                        <button type="button" id="startRecord" class="btn btn-danger">🎤 Start</button>
                                        <button type="button" id="stopRecord" class="btn btn-warning" disabled>⏹
                                            Stop</button>
                                    </div>
                                    <audio id="audioPlayback" controls class="mt-3 d-none"></audio>
                                    <div id="saveCancelBtns" class="mt-3 d-none">
                                        <button type="button" id="saveAudio" class="btn btn-success">✔ Save</button>
                                        <button type="button" id="cancelAudio" class="btn btn-danger">✖ Cancel</button>
                                    </div>
                                    <input type="hidden" name="audio_file" id="audioFileInput">
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
        // Audio Recording (unchanged)
        let mediaRecorder;
        let audioChunks = [];
        let audioBlob;

        const startBtn = document.getElementById("startRecord");
        const stopBtn = document.getElementById("stopRecord");
        const audioPlayback = document.getElementById("audioPlayback");
        const saveCancelBtns = document.getElementById("saveCancelBtns");
        const audioFileInput = document.getElementById("audioFileInput");

        if (startBtn && stopBtn) {
            startBtn.addEventListener("click", async () => {
                let stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(audioChunks, {
                        type: "audio/webm"
                    });
                    let audioURL = URL.createObjectURL(audioBlob);
                    audioPlayback.src = audioURL;
                    audioPlayback.classList.remove("d-none");
                    saveCancelBtns.classList.remove("d-none");
                };

                mediaRecorder.start();
                startBtn.disabled = true;
                stopBtn.disabled = false;
            });

            stopBtn.addEventListener("click", () => {
                mediaRecorder.stop();
                startBtn.disabled = false;
                stopBtn.disabled = true;
            });

            document.getElementById("saveAudio").addEventListener("click", () => {
                let reader = new FileReader();
                reader.readAsDataURL(audioBlob);
                reader.onloadend = () => {
                    audioFileInput.value = reader.result;
                };

                Swal.fire({
                    title: 'Saved!',
                    text: 'Audio recording saved temporarily.',
                    icon: 'success',
                    background: '#1a1a1a',
                    color: '#fff'
                });
            });

            document.getElementById("cancelAudio").addEventListener("click", () => {
                audioPlayback.src = "";
                audioPlayback.classList.add("d-none");
                saveCancelBtns.classList.add("d-none");
                audioFileInput.value = "";

                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Audio recording discarded.',
                    icon: 'warning',
                    background: '#1a1a1a',
                    color: '#fff'
                });
            });
        }
    </script>
@endsection
