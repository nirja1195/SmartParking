@extends('layouts.admin')

@section('title', 'Payment Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Payment Transactions & Revenue</h3>
        <p class="text-muted mb-0">Financial audit log, gateway transaction references, and settlement status</p>
    </div>
    <div class="card border-0 shadow-sm px-3 py-2 bg-success text-white">
        <small class="text-white-50">Total Successful Revenue</small>
        <h4 class="fw-bold mb-0">${{ number_format($totalRevenue, 2) }}</h4>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="method" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Payment Methods --</option>
                    @foreach(['Card', 'UPI', 'NetBanking', 'Wallet', 'PayAtGate'] as $m)
                        <option value="{{ $m }}" {{ request('method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Statuses --</option>
                    <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                    <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">Reset Filters</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Transaction Ref</th>
                    <th>Booking Ref</th>
                    <th>Customer</th>
                    <th>Facility</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td class="font-monospace fw-bold text-primary">{{ $p->transaction_id }}</td>
                        <td>
                            @if($p->booking)
                                <a href="{{ route('admin.bookings.show', $p->booking->id) }}" class="font-monospace text-decoration-none">
                                    #{{ $p->booking->booking_id }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $p->user->name ?? 'User' }}</div>
                            <small class="text-muted">{{ $p->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $p->booking->parkingLocation->name ?? 'N/A' }}</td>
                        <td class="fw-bold text-success">${{ number_format($p->amount, 2) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $p->payment_method }}</span>
                        </td>
                        <td>
                            <span class="badge 
                                @if($p->status == 'SUCCESS') bg-success 
                                @elseif($p->status == 'FAILED') bg-danger 
                                @else bg-warning @endif">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $p->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-credit-card-2-front display-5 d-block mb-3"></i>
                            No payment transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
