@extends('layout.app')

@section('content')
<div class="container">
    <h3 class="mb-4">My Shared Tasks</h3>

    {{-- POS Table --}}
    <h4>Shared POS</h4>
    <div class="table-responsive mb-5">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>POS Name</th>
                    <th>Business</th>
                    <th>Business Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posResults as $index => $pos)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pos->name ?? 'N/A' }}</td>
                        <td>{{ $pos->business_name ?? 'N/A' }}</td>
                        <td>{{ $pos->business_number ?? 'N/A' }}</td>
                        <td>{{ $pos->status ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('visitor.shared.pos.detail', $pos->id) }}" 
                               class="btn btn-sm btn-info rounded-pill">Show More</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No shared POS found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Account Table --}}
    <h4>Shared Accounts</h4>
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Business No</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accountResults as $index => $account)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $account->email ?? 'N/A' }}</td>
                        <td>{{ $account->phone ?? 'N/A' }}</td>
                        <td>{{ $account->bussiness_number ?? 'N/A' }}</td>
                        <td>{{ $account->status ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('visitor.shared.account.detail', $account->id) }}" 
                               class="btn btn-sm btn-info rounded-pill">Show More</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No shared Accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
