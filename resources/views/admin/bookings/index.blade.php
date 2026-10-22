@extends('layouts.admin')
@section('title', 'Bookings')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bookings.css') }}">
@endpush
@section('content')
@php($currency = \App\Models\Setting::get('currency', 'INR'))
<section class="bookings-page">
    <header class="bookings-heading"><div><h1>Bookings</h1><p>Manage all parking bookings. View, search, and track booking status.</p></div><button class="booking-new" type="button" data-bs-toggle="modal" data-bs-target="#newBooking"><i class="bi bi-plus-lg"></i> New Booking</button></header>
    <div class="booking-metrics">
        @foreach($metrics as $metric)
        <article class="booking-metric"><i class="bi bi-{{ $metric['icon'] }} metric-icon {{ $metric['tone'] }}"></i><div><span>{{ $metric['label'] }}</span><strong>{{ number_format($metric['count']) }}</strong><small>@if($metric['change'] !== null)<b class="{{ $metric['change'] < 0 ? 'negative' : '' }}">{{ $metric['change'] >= 0 ? '+' : '' }}{{ $metric['change'] }}%</b> vs last month @else No previous month data @endif</small></div></article>
        @endforeach
    </div>
    <form method="get" action="{{ route('admin.bookings.index') }}" class="booking-filters" id="bookingFilters">
        <label class="booking-search"><i class="bi bi-search"></i><input name="search" aria-label="Search bookings" placeholder="Search by booking ID, user name, or vehicle no..." value="{{ request('search') }}" maxlength="100"></label>
        <select name="location_id" aria-label="Location"><option value="">All Locations</option>@foreach($locations as $loc)<option value="{{ $loc->id }}" @selected(request('location_id') == $loc->id)>{{ $loc->name }}</option>@endforeach</select>
        <select name="booking_status" aria-label="Booking status"><option value="">All Status</option>@foreach(['PENDING','CONFIRMED','ACTIVE','COMPLETED','CANCELLED'] as $status)<option value="{{ $status }}" @selected(request('booking_status') === $status)>{{ ucfirst(strtolower($status)) }}</option>@endforeach</select>
        <details class="booking-date"><summary><i class="bi bi-calendar3"></i> {{ request('date_from') || request('date_to') ? 'Date range selected' : 'Select Date Range' }}</summary><div><label>From<input type="date" name="date_from" value="{{ request('date_from') }}"></label><label>To<input type="date" name="date_to" value="{{ request('date_to') }}"></label><button type="submit" class="booking-new">Apply dates</button></div></details>
        <a class="booking-reset" href="{{ route('admin.bookings.index') }}">Reset</a><noscript><button type="submit">Apply filters</button></noscript>
    </form>
    <div class="booking-table-card"><div class="booking-table-scroll"><table class="booking-table"><thead><tr><th>#</th><th>Booking ID</th><th>User Name</th><th>Vehicle No.</th><th>Location</th><th>Slot No.</th><th>Entry Time</th><th>Exit Time</th><th>Status</th><th>Amount</th><th>Actions</th></tr></thead><tbody>
    @forelse($bookings as $booking)
        <tr><td>{{ $bookings->firstItem() + $loop->index }}</td><td><a href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->booking_id }}</a></td><td>{{ $booking->user?->name ?? '—' }}</td><td>{{ $booking->vehicle?->vehicle_number ?? '—' }}</td><td>{{ $booking->parkingLocation?->name ?? '—' }}</td><td>{{ $booking->slot?->slot_number ?? '—' }}</td><td>@if($booking->entry_time){{ $booking->entry_time->format('d M Y,') }}<br>{{ $booking->entry_time->format('h:i A') }}@else — @endif</td><td>@php($exit = $booking->actual_exit_time ?? $booking->expected_exit_time)@if($exit){{ $exit->format('d M Y,') }}<br>{{ $exit->format('h:i A') }}@else — @endif</td><td><span class="booking-status {{ strtolower($booking->booking_status) }}">{{ ucfirst(strtolower($booking->booking_status)) }}</span></td><td class="booking-amount">{{ $currency }} {{ number_format($booking->amount, 2) }}</td><td><div class="booking-actions"><a href="{{ route('admin.bookings.show', $booking) }}" aria-label="View booking {{ $booking->booking_id }}" title="View booking"><i class="bi bi-eye"></i></a>@if(in_array($booking->booking_status, ['PENDING','CONFIRMED']))<form method="post" action="{{ route('admin.bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">@csrf<button class="cancel-booking" aria-label="Cancel booking {{ $booking->booking_id }}" title="Cancel booking"><i class="bi bi-x-circle"></i></button></form>@endif</div></td></tr>
    @empty
        <tr><td colspan="11" class="booking-empty"><i class="bi bi-calendar2"></i><strong>No bookings found</strong><span>Bookings matching your filters will appear here.</span></td></tr>
    @endforelse
    </tbody></table></div>
    <footer class="booking-pagination"><span>Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ number_format($bookings->total()) }} bookings</span><nav aria-label="Booking pages"><a href="{{ $bookings->previousPageUrl() ?? '#' }}" @if($bookings->onFirstPage()) aria-disabled="true" tabindex="-1" @endif aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>@for($page = max(1, $bookings->currentPage() - 2); $page <= min($bookings->lastPage(), $bookings->currentPage() + 2); $page++)<a href="{{ $bookings->url($page) }}" @if($page === $bookings->currentPage()) class="current" aria-current="page" @endif>{{ $page }}</a>@endfor<a href="{{ $bookings->nextPageUrl() ?? '#' }}" @if(!$bookings->hasMorePages()) aria-disabled="true" tabindex="-1" @endif aria-label="Next page"><i class="bi bi-chevron-right"></i></a></nav></footer>
    </div>
</section>
<div class="modal fade" id="newBooking" tabindex="-1" aria-labelledby="newBookingTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><form method="post" action="{{ route('admin.bookings.store') }}">@csrf<div class="modal-header"><h2 class="modal-title fs-5" id="newBookingTitle">New Booking</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">@if($errors->any())<div class="alert alert-danger" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<label class="form-label" for="bookingVehicle">Customer / vehicle</label><select class="form-select mb-3" name="vehicle_id" id="bookingVehicle" required><option value="">Select vehicle</option>@foreach($vehicles as $vehicle)<option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>{{ $vehicle->user?->name ?? 'No owner' }} — {{ $vehicle->vehicle_number }}</option>@endforeach</select>
<label class="form-label" for="bookingSlot">Available parking slot</label><select class="form-select mb-3" name="slot_id" id="bookingSlot" required><option value="">Select slot</option>@foreach($availableSlots as $slot)<option value="{{ $slot->id }}" @selected(old('slot_id') == $slot->id)>{{ $slot->parkingLocation?->name }} / {{ $slot->zone?->zone_name }} / {{ $slot->slot_number }}</option>@endforeach</select>
<label class="form-label" for="bookingEntry">Entry time</label><input class="form-control mb-3" type="datetime-local" name="entry_time" id="bookingEntry" value="{{ old('entry_time') }}" required><label class="form-label" for="bookingExit">Expected exit time</label><input class="form-control" type="datetime-local" name="expected_exit_time" id="bookingExit" value="{{ old('expected_exit_time') }}" required><p class="text-muted small mt-3 mb-0">The amount is calculated from the location's hourly rate. New bookings await payment.</p></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-warning" @disabled($vehicles->isEmpty() || $availableSlots->isEmpty())>Create Booking</button></div></form></div></div></div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filters = document.getElementById('bookingFilters');
    filters.querySelectorAll('select').forEach(select => select.addEventListener('change', () => filters.requestSubmit()));
    let timer;
    filters.elements.search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => filters.requestSubmit(), 650); });
    filters.addEventListener('submit', () => clearTimeout(timer));
    @if($errors->any() && old('slot_id'))
    new bootstrap.Modal(document.getElementById('newBooking')).show();
    @endif
});
</script>
@endpush