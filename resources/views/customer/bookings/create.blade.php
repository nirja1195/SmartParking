@extends('layouts.customer')

@section('title', 'Reserve a Parking Spot')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h4 class="fw-bold mb-2">Reserve a Parking Spot</h4>
            <p class="text-muted small mb-4">Choose your preferred parking garage, spot type, and duration.</p>

            <form method="POST" action="{{ route('customer.bookings.store') }}">
                @csrf

                <!-- Lot Selection -->
                <div class="mb-3">
                    <label for="parking_lot_id" class="form-label fw-semibold">1. Choose Parking Facility</label>
                    <select class="form-select" id="parking_lot_id" required onchange="filterSpots()">
                        <option value="">-- Select Parking Facility --</option>
                        @foreach($lots as $lot)
                            <option value="{{ $lot->id }}" data-rate="{{ $lot->hourly_rate }}" {{ request('lot_id') == $lot->id ? 'selected' : '' }}>
                                {{ $lot->name }} ({{ $lot->city }}) - ${{ number_format($lot->hourly_rate, 2) }}/hr
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Spot Selection -->
                <div class="mb-3">
                    <label for="parking_spot_id" class="form-label fw-semibold">2. Select Parking Spot</label>
                    <select class="form-select @error('parking_spot_id') is-invalid @enderror" id="parking_spot_id" name="parking_spot_id" required>
                        <option value="">-- Select Spot --</option>
                        @foreach($lots as $lot)
                            @foreach($lot->spots as $spot)
                                <option value="{{ $spot->id }}" data-lot="{{ $lot->id }}" data-rate="{{ $spot->getEffectiveRate() }}" class="spot-option" style="display: none;">
                                    Spot {{ $spot->spot_number }} (Floor: {{ $spot->floor }} &bull; {{ strtoupper($spot->spot_type) }}) - ${{ number_format($spot->getEffectiveRate(), 2) }}/hr
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('parking_spot_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Vehicle Selection -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="vehicle_id" class="form-label fw-semibold mb-0">3. Select Registered Vehicle</label>
                        <a href="{{ route('customer.vehicles.index') }}" class="small text-decoration-none">+ Add New Vehicle</a>
                    </div>
                    <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id">
                        <option value="">-- Optional / Select Vehicle --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">
                                {{ $v->make }} {{ $v->model }} (Plate: {{ $v->license_plate }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Time Interval -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label fw-semibold">4. Start Date & Time</label>
                        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="end_time" class="form-label fw-semibold">5. End Date & Time</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', now()->addHours(2)->format('Y-m-d\TH:i')) }}" required>
                        @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Live Price Estimate Card -->
                <div class="bg-light p-3 rounded mb-4 border d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Instant Settle & Guarantee</span>
                        <span class="fw-bold">Payment will be settled automatically upon confirmation.</span>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success fs-6 px-3 py-2">Instant Confirmation</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">Confirm & Reserve Spot</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterSpots() {
    const lotSelect = document.getElementById('parking_lot_id');
    const selectedLot = lotSelect.value;
    const spotSelect = document.getElementById('parking_spot_id');
    const options = spotSelect.getElementsByClassName('spot-option');

    spotSelect.value = '';
    let firstFound = false;

    for (let opt of options) {
        if (!selectedLot || opt.getAttribute('data-lot') === selectedLot) {
            opt.style.display = '';
            if (!firstFound && selectedLot) {
                spotSelect.value = opt.value;
                firstFound = true;
            }
        } else {
            opt.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    filterSpots();
});
</script>
@endsection
