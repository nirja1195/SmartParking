@extends('layouts.admin')

@section('title', 'System Analytics & Charts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Operational Analytics & Trends</h3>
        <p class="text-muted mb-0">Dynamic metrics powered by live MySQL records and Chart.js visualizations</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.ai-insights') }}" class="btn btn-primary">
            <i class="bi bi-cpu me-1"></i> AI Demand Prediction
        </a>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-text me-1"></i> Export Reports
        </a>
    </div>
</div>

<!-- Quick Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-success-subtle text-success me-3 fs-3">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <div>
                    <div class="text-muted small">Today's Gate Entries</div>
                    <h3 class="fw-bold mb-0">{{ $todayEntriesCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-info-subtle text-info me-3 fs-3">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <div>
                    <div class="text-muted small">Today's Gate Exits</div>
                    <h3 class="fw-bold mb-0">{{ $todayExitsCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-primary-subtle text-primary me-3 fs-3">
                    <i class="bi bi-p-square-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Active Bays Occupied</div>
                    <h3 class="fw-bold mb-0">{{ $slotDistribution['occupied'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-warning-subtle text-warning me-3 fs-3">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="text-muted small">Reserved Bays (Queued)</div>
                    <h3 class="fw-bold mb-0">{{ $slotDistribution['reserved'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-primary"></i> Daily Reservation Activity (Last 7 Days)</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="dailyBookingsChart" height="130"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill me-2 text-primary"></i> Live Slot Distribution</h5>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                <div style="width: 260px; height: 260px;">
                    <canvas id="slotDistChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i> Monthly Revenue Trend</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="revenueChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-primary"></i> Top Performing Facilities</h5>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    @forelse($popularLocations as $loc)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $loc->name }}</h6>
                                <small class="text-muted">{{ $loc->city }} &bull; ${{ number_format($loc->parking_charges, 2) }}/hr</small>
                            </div>
                            <span class="badge bg-primary fs-6">{{ $loc->bookings_count }} Bookings</span>
                        </li>
                    @empty
                        <li class="list-group-item px-0 py-3 text-muted">No facility records found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Daily Bookings
    const dailyLabels = {!! json_encode($dailyBookings->pluck('date')) !!};
    const dailyData = {!! json_encode($dailyBookings->pluck('total')) !!};

    const ctxDaily = document.getElementById('dailyBookingsChart').getContext('2d');
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: dailyLabels.length ? dailyLabels : ['No recent data'],
            datasets: [{
                label: 'Bookings',
                data: dailyData.length ? dailyData : [0],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 2. Slot Distribution Donut
    const ctxSlot = document.getElementById('slotDistChart').getContext('2d');
    new Chart(ctxSlot, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Reserved', 'Occupied', 'Maintenance', 'Blocked'],
            datasets: [{
                data: [
                    {{ $slotDistribution['available'] }},
                    {{ $slotDistribution['reserved'] }},
                    {{ $slotDistribution['occupied'] }},
                    {{ $slotDistribution['maintenance'] }},
                    {{ $slotDistribution['blocked'] }}
                ],
                backgroundColor: ['#10b981', '#2563eb', '#ef4444', '#6b7280', '#1f2937']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 3. Revenue Trend Bar
    const revLabels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
    const revData = {!! json_encode($monthlyRevenue->pluck('revenue')) !!};

    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'bar',
        data: {
            labels: revLabels.length ? revLabels : ['Current Month'],
            datasets: [{
                label: 'Revenue ($)',
                data: revData.length ? revData : [0],
                backgroundColor: '#10b981',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
