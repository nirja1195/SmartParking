@extends('layouts.admin')

@section('title', 'Transactions Log')
@section('page-title', 'Financial Transactions Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Payment Transactions Audit Log</h4>
        <p class="text-muted mb-0">Detailed transaction trace, payment IDs, gateway response details, and customer billing references.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-credit-card me-1"></i> Payment Summaries
        </a>
    </div>
</div>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by Transaction ID, Payment ID, Customer name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Txn ID</th>
                    <th>Payment ID</th>
                    <th>Booking Ref</th>
                    <th>Customer</th>
                    <th>Location / Slot</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td class="font-monospace fw-bold text-primary">{{ $txn->transaction_id ?? ('TXN-' . str_pad($txn->id, 8, '0', STR_PAD_LEFT)) }}</td>
                        <td class="font-monospace small text-muted">{{ $txn->payment_id ?? ('PAY-' . $txn->id) }}</td>
                        <td>
                            @if($txn->booking)
                                <a href="{{ route('admin.bookings.show', $txn->booking->id) }}" class="font-monospace text-decoration-none fw-semibold">
                                    #{{ $txn->booking->booking_id }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $txn->user->name ?? 'Customer' }}</div>
                            <small class="text-muted">{{ $txn->user->email ?? '' }}</small>
                        </td>
                        <td>
                            <div>{{ $txn->booking->parkingLocation->name ?? 'Main Hub' }}</div>
                            @if($txn->booking && $txn->booking->slot)
                                <span class="badge bg-primary fs-6">{{ $txn->booking->slot->slot_number }}</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">${{ number_format($txn->amount, 2) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $txn->payment_method }}</span>
                        </td>
                        <td>
                            <span class="badge 
                                @if($txn->status == 'SUCCESS') bg-success 
                                @elseif($txn->status == 'FAILED') bg-danger 
                                @else bg-warning @endif">
                                {{ $txn->status }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $txn->created_at ? $txn->created_at->format('M d, Y H:i:s') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt display-5 d-block mb-3"></i>
                            No financial transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
