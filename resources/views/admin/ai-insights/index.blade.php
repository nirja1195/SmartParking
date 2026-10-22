@extends('layouts.admin')

@section('title', 'AI Demand & Utilization Insights')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">AI Smart Demand Prediction</h3>
        <p class="text-muted mb-0">Predictive occupancy, peak hour intelligence, and dynamic parking bay optimization</p>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2">
        <i class="bi bi-cpu-fill me-1"></i> SMARTPARK AI Engine v2.4
    </span>
</div>

@if(!$insights['has_sufficient_data'])
    <!-- Insufficient Data State -->
    <div class="card border-0 shadow-sm py-5 text-center my-4">
        <div class="card-body p-5">
            <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex p-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill display-4"></i>
            </div>
            <h4 class="fw-bold text-dark">{{ $insights['message'] }}</h4>
            <p class="text-muted mx-auto" style="max-w: 600px;">
                The AI predictive engine requires at least <strong>{{ $insights['minimum_required'] }} historical booking records</strong> to calculate statistical demand curves, peak arrivals, and bay utilization accurately.
            </p>
            <div class="p-3 bg-light rounded d-inline-block border mb-4 font-monospace">
                Current Records in Database: <strong>{{ $insights['total_records'] }} / {{ $insights['minimum_required'] }}</strong>
            </div>
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-check me-1"></i> Check Bookings
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Sufficient Data AI Dashboard -->
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="bi bi-lightbulb-fill fs-3 text-info me-3"></i>
        <div>
            <strong>AI Optimization Recommendation:</strong>
            <p class="mb-0 small text-dark">{{ $insights['recommendation'] }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Peak Arrival Hours -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Predicted Peak Arrival Hours</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Based on recurring historical check-in timestamps, maximum parking rush occurs at:</p>

                    <div class="list-group list-group-flush">
                        @forelse($insights['peak_hours'] as $peak)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 rounded bg-danger-subtle text-danger me-3 fs-5">
                                        <i class="bi bi-fire"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ sprintf('%02d:00', $peak->hour) }} - {{ sprintf('%02d:00', ($peak->hour + 1) % 24) }}</h6>
                                        <small class="text-muted">High Inflow Window</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger fs-6">{{ $peak->total }} Arrivals</span>
                            </div>
                        @empty
                            <div class="text-muted py-3">No peak periods identified.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Type Preference Trends -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i> Vehicle Distribution Demand</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Vehicle category share across historical reservations:</p>

                    @foreach($insights['vehicle_trends'] as $trend)
                        @php
                            $totalTrend = max(1, $insights['total_records']);
                            $pct = round(($trend->count / $totalTrend) * 100);
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">{{ $trend->vehicle_type ?? 'Other' }}</span>
                                <span class="text-muted small">{{ $trend->count }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Location Utilization Scores -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-building-check me-2 text-primary"></i> Facility Utilization & Forecasted Demand</h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Parking Facility</th>
                        <th>Total Bookings</th>
                        <th>Utilization Index</th>
                        <th>Predicted Demand Level</th>
                        <th>Dynamic Strategy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($insights['location_demands'] as $loc)
                        <tr>
                            <td class="fw-bold fs-6">{{ $loc['name'] }}</td>
                            <td>{{ $loc['total_bookings'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px; max-width: 120px;">
                                        <div class="progress-bar bg-primary" style="width: {{ min(100, $loc['utilization_score']) }}%"></div>
                                    </div>
                                    <span class="small fw-semibold">{{ $loc['utilization_score'] }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($loc['predicted_demand'] == 'High') bg-danger 
                                    @elseif($loc['predicted_demand'] == 'Moderate') bg-warning text-dark 
                                    @else bg-success @endif">
                                    {{ $loc['predicted_demand'] }} Demand
                                </span>
                            </td>
                            <td class="small text-muted">
                                @if($loc['predicted_demand'] == 'High')
                                    <span class="text-danger fw-semibold">Implement surge tariff / Reserve extra bays</span>
                                @elseif($loc['predicted_demand'] == 'Moderate')
                                    <span>Standard operations and regular maintenance</span>
                                @else
                                    <span class="text-success fw-semibold">Consider discount promo to incentivize bookings</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
