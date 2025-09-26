@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #121217;
            --primary: #4f46e5;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #d1d5db;
            --border: #2d3748;
            --table-bg: rgba(31, 41, 55, 0.6);
            --hover-bg: rgba(75, 85, 99, 0.2);
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 1rem;
            border: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        .table th {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tbody tr:hover {
            background: var(--hover-bg);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .badge-danger {
            background: var(--danger);
        }

        .badge-warning {
            background: var(--warning);
        }

        .badge-success {
            background: var(--success);
        }

        .attachment-item img,
        .attachment-item video,
        .attachment-item audio {
            max-height: 80px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            transition: transform 0.3s ease;
        }

        .attachment-item img:hover,
        .attachment-item video:hover {
            transform: scale(1.05);
        }

        .attachment-item a {
            text-decoration: none;
        }

        .attachment-item img.icon {
            height: 40px;
        }

        .attachment-item div {
            font-size: 0.75rem;
            color: var(--text);
            margin-top: 0.25rem;
        }

        h2.text-center {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Client Details</h2>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Full Name</th>
                        <td>{{ $client->first_name . ' ' . $client->last_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $client->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Telephone</th>
                        <td>{{ $client->telephone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>{{ $client->date_of_birth ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>SIN</th>
                        <td>{{ $client->sin ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $client->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Mailing Address</th>
                        <td>{{ $client->mailing_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Marital Status</th>
                        <td>{{ $client->marital_status ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status in Canada</th>
                        <td>{{ $client->status_in_canada ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>ID (Driving/Passport)</th>
                        <td>{{ $client->ids_driving_passport ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>ID Expiry Date</th>
                        <td>{{ $client->ids_expiry_date ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Education</th>
                        <td>{{ $client->education ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Registered Name</th>
                        <td>{{ $client->corporation_registered_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fiscal Year T2</th>
                        <td>{{ $client->fiscal_year_t2 ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Ontario Corporation No</th>
                        <td>{{ $client->ontario_corporation_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fiscal Year HST</th>
                        <td>{{ $client->fiscal_year_hst ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Business Number</th>
                        <td>{{ $client->business_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Business Activities</th>
                        <td>{{ $client->business_activities ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date of Corporation</th>
                        <td>{{ $client->date_of_corporation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Key</th>
                        <td>{{ $client->corporation_key ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Registered in CRA For</th>
                        <td>{{ $client->register_in_cra_for ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Business Address</th>
                        <td>{{ $client->business_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Website</th>
                        <td>{{ $client->corporation_website ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Type</th>
                        <td>{{ $client->corporation_type ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Ontario Business/Corporation/Partnership</th>
                        <td>{{ $client->ontario_business_corporation_partnership ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Financial Institutions</th>
                        <td>{{ $client->financial_institutions ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Account No (Void Cheque)</th>
                        <td>{{ $client->account_no_void_cheque ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Credit Card Numbers From</th>
                        <td>{{ $client->credit_card_nos_from ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Outstanding Balance</th>
                        <td>{{ $client->outstanding_balance ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Loans from Institutions</th>
                        <td>{{ $client->loans_from_institutions ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Loan Outstanding Balance/Installment</th>
                        <td>{{ $client->loan_outstanding_balance_installment ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Mortgage From</th>
                        <td>{{ $client->mortgage_from ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Mortgage Outstanding Balance/Installment</th>
                        <td>{{ $client->mortgage_outstanding_balance_installment ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Auto Make/Year</th>
                        <td>{{ $client->auto_make_year ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Lease or Loan</th>
                        <td>{{ $client->lease_or_loan ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>WSIB Account No</th>
                        <td>{{ $client->wsib_account_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Client Introduced By</th>
                        <td>{{ $client->client_introduced_by ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $client->category ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>LMIA/Work Permit From</th>
                        <td>{{ $client->lmia_work_permit_from ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Service Charges/Fees</th>
                        <td>{{ $client->service_charges_fees ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Bookkeeping</th>
                        <td>{{ $client->bookkeeping?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Tax</th>
                        <td>{{ $client->corporation_tax?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>HST</th>
                        <td>{{ $client->hst?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Financials</th>
                        <td>{{ $client->financials?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Personal Tax</th>
                        <td>{{ $client->personal_tax?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Immigration</th>
                        <td>{{ $client->immigration?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Registration</th>
                        <td>{{ $client->corporation_registration?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Accounting</th>
                        <td>{{ $client->accounting?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>MH Enterprises Signature</th>
                        <td>{{ $client->mh_enterprises_signature ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Client Signature</th>
                        <td>{{ $client->client_signature ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $client->status == 'active' ? 'success' : ($client->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($client->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- <div class="mt-4">
            <a href="{{ route('project_manager.clients.index') }}" class="btn btn-secondary">Back to Clients</a>
        </div> --}}
    </div>
@endsection