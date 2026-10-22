@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Notifications</h3>
        <p class="text-muted mb-0">System alerts, booking confirmations, gate entries, and payment receipts</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse($notifications as $item)
            <div class="p-3 border-bottom d-flex align-items-start {{ $item->is_read ? '' : 'bg-light' }}">
                <div class="rounded-circle p-2 me-3 fs-5 
                    @if($item->type == 'BOOKING') bg-primary-subtle text-primary 
                    @elseif($item->type == 'ENTRY') bg-info-subtle text-info 
                    @elseif($item->type == 'EXIT') bg-success-subtle text-success 
                    @elseif($item->type == 'PAYMENT') bg-warning-subtle text-warning 
                    @else bg-secondary-subtle text-secondary @endif">
                    @if($item->type == 'BOOKING') <i class="bi bi-bookmark-check-fill"></i>
                    @elseif($item->type == 'ENTRY') <i class="bi bi-box-arrow-in-right"></i>
                    @elseif($item->type == 'EXIT') <i class="bi bi-box-arrow-right"></i>
                    @elseif($item->type == 'PAYMENT') <i class="bi bi-credit-card-2-front-fill"></i>
                    @else <i class="bi bi-bell-fill"></i> @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-1">{{ $item->title }}</h6>
                        <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="text-muted mb-1 small">{{ $item->message }}</p>
                    <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.75rem;">{{ $item->type }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash display-4 text-muted mb-3"></i>
                <h5 class="fw-bold">No Notifications</h5>
                <p class="text-muted">You're all caught up! Updates regarding your bookings and gate movements will appear here.</p>
            </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
