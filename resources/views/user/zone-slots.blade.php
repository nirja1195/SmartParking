@extends('layouts.user')

@section('title', $location->name.' - '.$zone->zone_name.' Slots')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('user.parking.show', $location->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Zones
        </a>
        <div>
            <span class="badge bg-primary fs-6">{{ $location->name }}</span>
            <span class="badge bg-dark fs-6">{{ $zone->zone_name }}</span>
        </div>
    </div>

    <!-- Visual Legend (Requirement #16) -->
    <div class="card p-3 mb-4 border">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="fw-bold small text-muted text-uppercase">Slot Status Legend:</span>
            <div class="d-flex flex-wrap gap-3 small">
                <span class="d-flex align-items-center"><span class="badge me-1" style="background:#86efac; color:#15803d;">&nbsp;&bull;&nbsp;</span> <strong>Green:</strong> Available</span>
                <span class="d-flex align-items-center"><span class="badge me-1" style="background:#93c5fd; color:#1e40af;">&nbsp;&bull;&nbsp;</span> <strong>Blue:</strong> Reserved</span>
                <span class="d-flex align-items-center"><span class="badge me-1" style="background:#fca5a5; color:#991b1b;">&nbsp;&bull;&nbsp;</span> <strong>Red:</strong> Occupied</span>
                <span class="d-flex align-items-center"><span class="badge me-1" style="background:#cbd5e1; color:#64748b;">&nbsp;&bull;&nbsp;</span> <strong>Gray:</strong> Maintenance</span>
                <span class="d-flex align-items-center"><span class="badge me-1" style="background:#1e293b; color:#ffffff;">&nbsp;&bull;&nbsp;</span> <strong>Dark Gray:</strong> Blocked</span>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('user.booking.review') }}" id="bookingForm">
        <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
        <input type="hidden" name="zone_id" value="{{ $zone->id }}">
        <input type="hidden" name="slot_id" id="selected_slot_id" required>

        <div class="row g-4">
            <!-- Visual Slot Grid (Requirement #20) -->
            <div class="col-lg-8">
                <div class="card p-4 shadow-sm border">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Interactive Parking Grid &bull; {{ $zone->zone_name }}</h5>
                        <small class="text-muted">Click an AVAILABLE (green) slot to select</small>
                    </div>

                    <div class="row g-3" id="slotGridContainer">
                        @forelse($slots as $slot)
                            <div class="col-6 col-sm-4 col-md-3">
                                @if($slot->status === 'AVAILABLE')
                                    <!-- Available Slot: Clickable -->
                                    <div class="slot-box slot-available" 
                                         onclick="selectSlot({{ $slot->id }}, '{{ $slot->slot_number }}')" 
                                         id="slot_card_{{ $slot->id }}"
                                         title="Slot {{ $slot->slot_number }} is Available. Click to choose.">
                                        <i class="bi bi-p-square mb-1 fs-5"></i>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                        <small style="font-size: 0.65rem;">AVAILABLE</small>
                                    </div>
                                @elseif($slot->status === 'RESERVED')
                                    <div class="slot-box slot-reserved" title="Slot {{ $slot->slot_number }} is currently Reserved.">
                                        <i class="bi bi-clock-history mb-1 fs-5"></i>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                        <small style="font-size: 0.65rem;">RESERVED</small>
                                    </div>
                                @elseif($slot->status === 'OCCUPIED')
                                    <div class="slot-box slot-occupied" title="Slot {{ $slot->slot_number }} is currently Occupied.">
                                        <i class="bi bi-car-front-fill mb-1 fs-5"></i>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                        <small style="font-size: 0.65rem;">OCCUPIED</small>
                                    </div>
                                @elseif($slot->status === 'MAINTENANCE')
                                    <div class="slot-box slot-maintenance" title="Under Maintenance">
                                        <i class="bi bi-tools mb-1 fs-5"></i>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                        <small style="font-size: 0.65rem;">MAINTENANCE</small>
                                    </div>
                                @else
                                    <div class="slot-box slot-blocked" title="Blocked">
                                        <i class="bi bi-slash-circle mb-1 fs-5"></i>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                        <small style="font-size: 0.65rem;">BLOCKED</small>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center text-muted">
                                No slots configured in this zone yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Booking Options Sidebar -->
            <div class="col-lg-4">
                <div class="card p-4 shadow-sm border sticky-top" style="top: 80px;">
                    <h5 class="fw-bold mb-3 text-dark">Reservation Details</h5>

                    <!-- Selected Slot Indicator -->
                    <div class="p-3 bg-light rounded mb-3 border text-center">
                        <span class="text-muted small d-block mb-1">Selected Parking Spot:</span>
                        <div id="selected_slot_display" class="fs-4 fw-bold text-primary">
                            None selected
                        </div>
                        <small class="text-muted" id="selected_slot_hint">Please click any green slot on the grid.</small>
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="vehicle_id" class="form-label small fw-semibold text-muted mb-0">Vehicle</label>
                            <a href="{{ route('user.vehicles') }}" class="small text-decoration-none">+ Add</a>
                        </div>
                        <select class="form-select" id="vehicle_id" name="vehicle_id">
                            <option value="">-- Optional / Select Vehicle --</option>
                            @foreach($vehicles as $veh)
                                <option value="{{ $veh->id }}" {{ $veh->is_default ? 'selected' : '' }}>
                                    {{ $veh->vehicle_number }} ({{ $veh->vehicle_model ?? $veh->vehicle_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-3">
                        <label for="booking_date" class="form-label small fw-semibold text-muted">Booking Date</label>
                        <input type="date" class="form-control" id="booking_date" name="booking_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Entry Time -->
                    <div class="mb-3">
                        <label for="entry_time" class="form-label small fw-semibold text-muted">Expected Entry Time</label>
                        <input type="datetime-local" class="form-control" id="entry_time" name="entry_time" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <!-- Exit Time -->
                    <div class="mb-4">
                        <label for="expected_exit_time" class="form-label small fw-semibold text-muted">Expected Exit Time</label>
                        <input type="datetime-local" class="form-control" id="expected_exit_time" name="expected_exit_time" value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" id="btnReviewBooking" disabled>
                        PROCEED TO REVIEW <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let selectedSlotId = null;

function selectSlot(id, number) {
    // Remove selected styling from previous
    if (selectedSlotId) {
        const prevCard = document.getElementById('slot_card_' + selectedSlotId);
        if (prevCard) {
            prevCard.style.outline = 'none';
        }
    }

    selectedSlotId = id;
    document.getElementById('selected_slot_id').value = id;
    document.getElementById('selected_slot_display').innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Slot ' + number + '</span>';
    document.getElementById('selected_slot_hint').innerText = 'Rate: ${{ number_format($location->parking_charges, 2) }}/hr';
    document.getElementById('btnReviewBooking').disabled = false;

    // Add highlight
    const currentCard = document.getElementById('slot_card_' + id);
    if (currentCard) {
        currentCard.style.outline = '3px solid #2563EB';
    }
}
</script>
@endpush
@endsection
