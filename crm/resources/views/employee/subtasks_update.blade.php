@extends('layout.app')

<style>
    .blur-bg {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

@section('content')
    <div class="container mt-4">
        <h3 class="text-white mb-4">Update Subtask by Lead</h3>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        @foreach ($leadValues as $lead)
            @php
                $index = $lead - 1;
                $empSubtask = $employeeSubtask;
                $commentVal = old('comment', $empSubtask->comments[$index] ?? '');
                $statusVal = $empSubtask->statuses[$index] ?? 'pending';
                $attachmentUrls = $empSubtask->attachments[$index] ?? [];
            @endphp

            <h4 class="text-warning mt-4">Lead {{ $lead }}</h4>

            <form action="{{ route('employee.subtask.update', ['id' => $subtask->id]) }}" method="POST"
                enctype="multipart/form-data" class="mb-5 p-4 border rounded blur-bg">
                @csrf
                @method('PUT')

                <input type="hidden" name="lead" value="{{ $lead }}">

                <div class="mb-3">
                    <label class="form-label text-white">Title</label>
                    <input class="form-control" value="{{ $subtask->title }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Description</label>
                    <textarea class="form-control" readonly rows="2">{{ $subtask->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Comment</label>
                    <textarea name="comment" class="form-control">{{ $commentVal }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $statusVal == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $statusVal == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $statusVal == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                @if ($isCallCenter)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $employeeSubtask->name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Business Name</label>
                            <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $employeeSubtask->business_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Business Number</label>
                            <input type="text" name="business_num" class="form-control" value="{{ old('business_num', $employeeSubtask->business_num) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Personal Number</label>
                            <input type="text" name="personal_num" class="form-control" value="{{ old('personal_num', $employeeSubtask->personal_num) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Personal Email</label>
                            <input type="email" name="personal_email" class="form-control" value="{{ old('personal_email', $employeeSubtask->personal_email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Business Email</label>
                            <input type="email" name="business_email" class="form-control" value="{{ old('business_email', $employeeSubtask->business_email) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">Address</label>
                            <textarea name="address" class="form-control">{{ old('address', $employeeSubtask->address) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Perivos</label>
                            <input type="text" name="perivos" class="form-control" value="{{ old('perivos', $employeeSubtask->perivos) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Provider</label>
                            <input type="text" name="provider" class="form-control" value="{{ old('provider', $employeeSubtask->provider) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Category POS</label>
                            <input type="text" name="category_pos" class="form-control" value="{{ old('category_pos', $employeeSubtask->category_pos) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">POS Type</label>
                            <input type="text" name="pos_type" class="form-control" value="{{ old('pos_type', $employeeSubtask->pos_type) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Debt</label>
                            <input type="text" name="debt" class="form-control" value="{{ old('debt', $employeeSubtask->debt) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Credit</label>
                            <input type="text" name="credit" class="form-control" value="{{ old('credit', $employeeSubtask->credit) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Rentle</label>
                            <input type="text" name="rentle" class="form-control" value="{{ old('rentle', $employeeSubtask->rentle) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Oppiomennt Date</label>
                            <input type="date" name="oppiomennt_date" class="form-control" value="{{ old('oppiomennt_date', $employeeSubtask->oppiomennt_date) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-white">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $employeeSubtask->date) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-white">Time</label>
                            <input type="time" name="time" class="form-control" value="{{ old('time', $employeeSubtask->time) }}">
                        </div>
                    </div>
                @endif

                @if (count($attachmentUrls))
                    <div class="mb-3 text-white">
                        <label class="form-label">Attachments</label>
                        <div class="row g-2">
                            @foreach ($attachmentUrls as $url)
                                @php
                                    $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
                                    $isAudio = in_array($ext, ['mp3', 'wav', 'ogg']);
                                    $isDoc = in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']);
                                @endphp
                                <div class="col-md-4">
                                    <div class="p-2 border border-light rounded">
                                        @if ($isImage)
                                            <img src="{{ $url }}" class="img-fluid rounded"
                                                style="height: 180px; object-fit: contain;">
                                        @elseif($isVideo)
                                            <video controls class="w-100 rounded"
                                                style="height: 180px; object-fit: contain;">
                                                <source src="{{ $url }}">
                                            </video>
                                        @elseif($isAudio)
                                            <audio controls class="w-100 mt-2">
                                                <source src="{{ $url }}">
                                            </audio>
                                        @elseif($isDoc)
                                            <a href="{{ $url }}" target="_blank"
                                                class="btn btn-info btn-sm w-100 mt-2">View Document</a>
                                        @else
                                            <a href="{{ $url }}" target="_blank"
                                                class="btn btn-secondary btn-sm w-100 mt-2">Download File</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-white">Upload New Attachments</label>
                    <input type="file" name="attachments[]" multiple class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Lead {{ $lead }}</button>
            </form>
        @endforeach

    </div>
@endsection
