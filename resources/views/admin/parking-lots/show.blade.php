@extends('layouts.admin')

@section('title', $lot->name.' - Spots')
@section('page-title', $lot->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ $lot->name }} ({{ $lot->code }})</h4>
        <p class="text-muted small mb-0">{{ $lot->address }}, {{ $lot->city }} &bull; Rate: ${{ number_format($lot->hourly_rate, 2) }}/hr</p>
    </div>
    <a href="{{ route('admin.parking-lots.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Facilities
    </a>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Spot Layout & Occupancy</h5>
        <div class="d-flex gap-3 small">
            <span class="d-flex align-items-center"><span class="badge bg-success me-1">&nbsp;</span> Available</span>
            <span class="d-flex align-items-center"><span class="badge bg-danger me-1">&nbsp;</span> Occupied</span>
            <span class="d-flex align-items-center"><span class="badge bg-secondary me-1">&nbsp;</span> Inactive</span>
        </div>
    </div>

    <div class="row g-3">
        @foreach($lot->spots as $spot)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card text-center p-3 {{ $spot->is_occupied ? 'border-danger bg-danger-subtle' : ($spot->is_active ? 'border-success bg-success-subtle' : 'border-secondary bg-light') }}">
                    <div class="fw-bold fs-5">{{ $spot->spot_number }}</div>
                    <div class="text-muted small">{{ $spot->floor }}</div>
                    <span class="badge bg-dark mt-2" style="font-size: 0.7rem;">{{ strtoupper($spot->spot_type) }}</span>
                    <div class="small fw-semibold mt-1">
                        {{ $spot->is_occupied ? 'Occupied' : 'Vacant' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
