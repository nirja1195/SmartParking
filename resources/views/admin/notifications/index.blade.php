@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">System Notifications</h3>
        <p class="text-muted mb-0">View and manage all system and user notifications</p>
    </div>
    <div>
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notifications as $n)
                <div class="list-group-item p-3 {{ !$n->is_read ? 'bg-light' : '' }} d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-2 rounded-circle bg-warning-subtle text-warning fs-5">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $n->title }}</h6>
                            <p class="mb-1 text-secondary small">{{ $n->message }}</p>
                            <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @if(!$n->is_read)
                        <form action="{{ route('admin.notifications.mark-read', $n->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light border text-muted">
                                Mark Read
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-4 text-center text-muted">No notifications found.</div>
            @endforelse
        </div>
    </div>
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
