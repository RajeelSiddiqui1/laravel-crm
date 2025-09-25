@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-light display-6">{{ $subtask->title }} - Details</h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-light btn-lg rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>

    @if ($subtask->attachments)
    <div class="card bg-glass mb-5 shadow-lg border-0">
        <div class="card-body p-4">
            <h4 class="text-light fw-semibold mb-3">Subtask Attachment</h4>
            <div class="attachment-container">
                @php
                    $subtaskExt = strtolower(pathinfo(parse_url($subtask->attachments, PHP_URL_PATH), PATHINFO_EXTENSION));
                @endphp
                @if (in_array($subtaskExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <a href="{{ $subtask->attachments }}" data-lightbox="subtask-attachment">
                        <img src="{{ $subtask->attachments }}" alt="Subtask Attachment" class="img-fluid rounded shadow-sm" style="max-height: 300px; width: auto;">
                    </a>
                    <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                        <i class="bi bi-download me-2"></i> Download Image
                    </a>
                @elseif (in_array($subtaskExt, ['mp4', 'mov', 'avi', 'webm']))
                    <div class="media-wrapper">
                        <video controls class="w-100 rounded shadow-sm" style="max-height: 300px;">
                            <source src="{{ $subtask->attachments }}" type="video/{{ $subtaskExt }}">
                        </video>
                        <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                            <i class="bi bi-download me-2"></i> Download Video
                        </a>
                    </div>
                @elseif (in_array($subtaskExt, ['mp3', 'wav', 'ogg']))
                    <div class="media-wrapper">
                        <audio controls class="w-100">
                            <source src="{{ $subtask->attachments }}" type="audio/{{ $subtaskExt }}">
                        </audio>
                        <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                            <i class="bi bi-download me-2"></i> Download Audio
                        </a>
                    </div>
                @else
                    <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i> Download File ({{ strtoupper($subtaskExt) }})
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if ($posRecords->isNotEmpty())
        <h4 class="mt-5 mb-4 text-light fw-semibold">Call Center POS Records</h4>
        @forelse($posRecords as $pos)
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-glass shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-light mb-0">{{ $pos->name ?? 'N/A' }}</h5>
                                <span class="badge {{ $pos->status === 'active' ? 'bg-success' : ($pos->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $pos->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Employee:</strong> {{ $pos->employee->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comment:</strong> {{ $pos->comment ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business:</strong> {{ $pos->business_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Number:</strong> {{ $pos->business_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Personal Number:</strong> {{ $pos->personal_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Personal Email:</strong> {{ $pos->personal_email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Email:</strong> {{ $pos->business_email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Address:</strong> {{ $pos->address ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Provider:</strong> {{ $pos->provider ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Category:</strong> {{ $pos->category_pos ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>POS Type:</strong> {{ $pos->pos_type ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Debt:</strong> ${{ number_format($pos->debt ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Credit:</strong> ${{ number_format($pos->credit ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Rental:</strong> ${{ number_format($pos->rental ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Business Type:</strong> {{ $pos->business_type ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Date:</strong> {{ $pos->date ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Time:</strong> {{ $pos->time ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @php
                                $attachments = $pos->attachments;
                                if (is_string($attachments)) {
                                    $decoded = json_decode($attachments, true);
                                    $attachments = is_array($decoded) ? $decoded : [$attachments];
                                } elseif (!is_array($attachments)) {
                                    $attachments = $attachments ? [$attachments] : [];
                                }
                            @endphp
                            @if(!empty($attachments))
                                <div class="row g-3 mt-4">
                                    @foreach($attachments as $file)
                                        @php
                                            $fileUrl = Str::startsWith($file, ['http://', 'https://'])
                                                ? $file
                                                : asset('storage/' . $file);
                                            $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                        @endphp
                                        <div class="col-12 col-md-4 text-center">
                                            <div class="attachment-item">
                                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                    <a href="{{ $fileUrl }}" data-lightbox="pos-attachment-{{ $pos->id }}">
                                                        <img src="{{ $fileUrl }}" alt="Image" class="img-fluid rounded">
                                                    </a>
                                                @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                                                    <video src="{{ $fileUrl }}" controls class="w-100 rounded"></video>
                                                @elseif(in_array($ext, ['mp3','wav','ogg']))
                                                    <audio controls class="w-100">
                                                        <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif(in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['xls','xlsx','csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['doc','docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No Attachments</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @endif

    @if ($accountRecords->isNotEmpty())
        <h4 class="mt-5 mb-4 text-light fw-semibold">Call Center Account Records</h4>
        @forelse($accountRecords as $account)
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-glass shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-light mb-0">{{ $account->email ?? 'N/A' }}</h5>
                                <span class="badge {{ $account->status === 'active' ? 'bg-success' : ($account->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $account->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Employee:</strong> {{ $account->employee->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comments:</strong> {{ $account->comments ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Phone:</strong> {{ $account->phone ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $account->email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Number:</strong> {{ $account->business_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Corporation Number:</strong> {{ $account->corporation_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Email:</strong> {{ $account->corporation_email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Documents:</strong> {{ $account->corporation_documents ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Previous History:</strong> {{ $account->previous_history ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Fees:</strong> ${{ number_format($account->fees ?? 0, 2) }}</p>
                                </div>
                            </div>
                            @php
                                $attachments = $account->attachments;
                                if (is_string($attachments)) {
                                    $decoded = json_decode($attachments, true);
                                    $attachments = is_array($decoded) ? $decoded : [$attachments];
                                } elseif (!is_array($attachments)) {
                                    $attachments = $attachments ? [$attachments] : [];
                                }
                            @endphp
                            @if(!empty($attachments))
                                <div class="row g-3 mt-4">
                                    @foreach($attachments as $file)
                                        @php
                                            $fileUrl = Str::startsWith($file, ['http://', 'https://'])
                                                ? $file
                                                : asset('storage/' . $file);
                                            $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                        @endphp
                                        <div class="col-12 col-md-4 text-center">
                                            <div class="attachment-item">
                                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                    <a href="{{ $fileUrl }}" data-lightbox="account-attachment-{{ $account->id }}">
                                                        <img src="{{ $fileUrl }}" alt="Image" class="img-fluid rounded">
                                                    </a>
                                                @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                                                    <video src="{{ $fileUrl }}" controls class="w-100 rounded"></video>
                                                @elseif(in_array($ext, ['mp3','wav','ogg']))
                                                    <audio controls class="w-100">
                                                        <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif(in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['xls','xlsx','csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['doc','docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No Attachments</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @endif

    @if ($clientDetailRecords->isNotEmpty())
        <h4 class="mt-5 mb-4 text-light fw-semibold">Client Detail Records</h4>
        @forelse($clientDetailRecords as $client)
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-glass shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-light mb-0">{{ $client->first_name ?? 'N/A' }} {{ $client->last_name ?? '' }}</h5>
                                <span class="badge {{ $client->status === 'active' ? 'bg-success' : ($client->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $client->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Employee:</strong> {{ $client->employee->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comments:</strong> {{ $client->comments ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>First Name:</strong> {{ $client->first_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Last Name:</strong> {{ $client->last_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Telephone:</strong> {{ $client->telephone ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Date of Birth:</strong> {{ $client->date_of_birth ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>SIN:</strong> {{ $client->sin ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Address:</strong> {{ $client->address ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Mailing Address:</strong> {{ $client->mailing_address ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Marital Status:</strong> {{ $client->marital_status ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Status in Canada:</strong> {{ $client->status_in_canada ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>ID (Driving License/Passport):</strong> {{ $client->ids_driving_passport ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>ID Expiry Date:</strong> {{ $client->ids_expiry_date ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Education:</strong> {{ $client->education ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Registered Name:</strong> {{ $client->corporation_registered_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Fiscal Year T2:</strong> {{ $client->fiscal_year_t2 ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Ontario Corporation No:</strong> {{ $client->ontario_corporation_no ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Fiscal Year HST:</strong> {{ $client->fiscal_year_hst ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business No:</strong> {{ $client->business_no ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Business Activities:</strong> {{ $client->business_activities ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Date of Corporation:</strong> {{ $client->date_of_corporation ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Key:</strong> {{ $client->corporation_key ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Register in CRA For:</strong> {{ $client->register_in_cra_for ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Address:</strong> {{ $client->business_address ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Website:</strong> {{ $client->corporation_website ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Type:</strong> {{ $client->corporation_type ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Ontario Business/Corporation/Partnership:</strong> {{ $client->ontario_business_corporation_partnership ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Financial Institutions:</strong> {{ $client->financial_institutions ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Account No/Void Cheque:</strong> {{ $client->account_no_void_cheque ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Credit Card Numbers From:</strong> {{ $client->credit_card_nos_from ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Outstanding Balance:</strong> ${{ number_format($client->outstanding_balance ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Loans From Institutions:</strong> {{ $client->loans_from_institutions ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Loan Outstanding Balance/Installment:</strong> ${{ number_format($client->loan_outstanding_balance_installment ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Mortgage From:</strong> {{ $client->mortgage_from ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Mortgage Outstanding Balance/Installment:</strong> ${{ number_format($client->mortgage_outstanding_balance_installment ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Auto Make & Year:</strong> {{ $client->auto_make_year ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Lease or Loan:</strong> {{ $client->lease_or_loan ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>WSIB Account No:</strong> {{ $client->wsib_account_no ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Client Introduced By:</strong> {{ $client->client_introduced_by ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Category:</strong> {{ $client->category ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>LMIA/Work Permit From:</strong> {{ $client->lmia_work_permit_from ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Service Charges/Fees:</strong> ${{ number_format($client->service_charges_fees ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Bookkeeping:</strong> ${{ number_format($client->bookkeeping ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Corporation Tax:</strong> ${{ number_format($client->corporation_tax ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>HST:</strong> ${{ number_format($client->hst ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Financials:</strong> ${{ number_format($client->financials ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Personal Tax:</strong> ${{ number_format($client->personal_tax ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Immigration:</strong> ${{ number_format($client->immigration ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Corporation Registration:</strong> ${{ number_format($client->corporation_registration ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Accounting:</strong> ${{ number_format($client->accounting ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>MH Enterprises Signature:</strong> {{ $client->mh_enterprises_signature ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Client Signature:</strong> {{ $client->client_signature ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @php
                                $attachments = $client->attachments;
                                if (is_string($attachments)) {
                                    $decoded = json_decode($attachments, true);
                                    $attachments = is_array($decoded) ? $decoded : [$attachments];
                                } elseif (!is_array($attachments)) {
                                    $attachments = $attachments ? [$attachments] : [];
                                }
                            @endphp
                            @if(!empty($attachments))
                                <div class="row g-3 mt-4">
                                    @foreach($attachments as $file)
                                        @php
                                            $fileUrl = Str::startsWith($file, ['http://', 'https://'])
                                                ? $file
                                                : asset('storage/' . $file);
                                            $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                        @endphp
                                        <div class="col-12 col-md-4 text-center">
                                            <div class="attachment-item">
                                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                    <a href="{{ $fileUrl }}" data-lightbox="client-attachment-{{ $client->id }}">
                                                        <img src="{{ $fileUrl }}" alt="Image" class="img-fluid rounded">
                                                    </a>
                                                @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                                                    <video src="{{ $fileUrl }}" controls class="w-100 rounded"></video>
                                                @elseif(in_array($ext, ['mp3','wav','ogg']))
                                                    <audio controls class="w-100">
                                                        <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif(in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['xls','xlsx','csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['doc','docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No Attachments</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @endif
</div>
@endsection

@section('styles')
<style>
.bg-glass { background: rgba(255,255,255,0.15); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.bg-glass:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
body { background: linear-gradient(135deg,#1e3a8a,#3b82f6); min-height:100vh; font-family:'Inter',sans-serif; }
.text-light { color: #f8f9fa !important; }
.card-title { font-size:1.5rem; font-weight:600; }
.badge { font-size:0.9rem; padding:0.5em 1em; border-radius:12px; }
.attachment-container { display:flex; flex-direction:column; align-items:flex-start; gap:1rem; }
.attachment-item { background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; }
.icon { width: 48px; height: 48px; margin-bottom: 8px; }
.media-wrapper { width:100%; max-width:500px; }
.img-fluid, video, audio { max-width:100%; height:auto; border-radius:8px; }
.btn-outline-light { border-color: rgba(255,255,255,0.7); color:#f8f9fa; transition: background 0.3s ease, transform 0.2s ease; }
.btn-outline-light:hover { background: rgba(255,255,255,0.3); color:#fff; transform: scale(1.05); }
@media(max-width:768px){ .card-title{font-size:1.25rem;} .btn-outline-light{font-size:0.9rem;} .img-fluid, video{max-height:200px;} }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script>
lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'disableScrolling': true });
</script>
@endsection