@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">
    <!-- 3. DASHBOARD WELCOME BANNER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width: 20px; height: 3px; background-color: var(--sp-yellow); border-radius: 2px;"></div>
                <span class="text-uppercase fw-bold text-secondary" style="font-size: 0.72rem; letter-spacing: 0.08em;">ADMIN PANEL</span>
            </div>
            <h2 class="fw-extrabold text-dark mb-1" style="font-size: 2rem; letter-spacing: -0.02em;">
                Welcome Back, <span class="text-yellow">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}!</span>
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Here’s what’s happening with your parking system today.</p>
        </div>

        <!-- Date Selector Dropdown -->
        <div class="dropdown">
            <button class="btn btn-white bg-white border shadow-sm rounded-3 px-3 py-2 d-flex align-items-center gap-2 font-semibold text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-calendar-event text-secondary"></i>
                <span style="font-size: 0.9rem;">{{ $selectedDate ? $selectedDate->format('D, d M Y') : \Carbon\Carbon::now()->format('D, d M Y') }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                <li><a class="dropdown-item py-2 {{ $selectedPeriod === '7_days' ? 'active bg-warning text-dark fw-bold' : '' }}" href="{{ route('admin.dashboard', ['period' => '7_days']) }}">Last 7 Days</a></li>
                <li><a class="dropdown-item py-2 {{ $selectedPeriod === '30_days' ? 'active bg-warning text-dark fw-bold' : '' }}" href="{{ route('admin.dashboard', ['period' => '30_days']) }}">Last 30 Days</a></li>
                <li><a class="dropdown-item py-2 {{ $selectedPeriod === 'this_month' ? 'active bg-warning text-dark fw-bold' : '' }}" href="{{ route('admin.dashboard', ['period' => 'this_month']) }}">This Month</a></li>
            </ul>
        </div>
    </div>

    <!-- 4. FOUR STAT CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Vehicles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Total Vehicles</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['total_vehicles']) }}</h3>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-caret-up-fill"></i> {{ $stats['vehicles_pct']['formatted'] }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.75rem;">vs last week</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Users -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Total Users</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['total_users']) }}</h3>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-caret-up-fill"></i> {{ $stats['users_pct']['formatted'] }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.75rem;">vs last week</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Occupied Slots -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3">
                    <i class="bi bi-p-circle-fill"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Occupied Slots</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['occupied_slots']) }}</h3>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-caret-up-fill"></i> {{ $stats['occupied_pct']['formatted'] }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.75rem;">vs last week</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Revenue -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3">
                    <i class="bi bi-credit-card-fill"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Total Revenue</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.5rem;">₹ {{ number_format($stats['total_revenue'], 0) }}</h3>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-caret-up-fill"></i> {{ $stats['revenue_pct']['formatted'] }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.75rem;">vs last week</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MIDDLE ROW: PARKING ACTIVITY CHART & SLOT STATUS DONUT -->
    <div class="row g-4 mb-4">
        <!-- 5. PARKING ACTIVITY CHART -->
        <div class="col-12 col-lg-8">
            <div class="card-custom p-4 h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Parking Activity</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: var(--sp-yellow);"></span>
                            <span class="text-secondary fw-medium">Entries</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: var(--sp-dark);"></span>
                            <span class="text-secondary fw-medium">Exits</span>
                        </div>

                        <!-- Period selector dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-white bg-white border shadow-sm rounded-2 dropdown-toggle text-secondary fw-medium px-2 py-1" style="font-size: 0.8rem;" type="button" data-bs-toggle="dropdown">
                                {{ $selectedPeriod === '30_days' ? 'Last 30 Days' : ($selectedPeriod === 'this_month' ? 'This Month' : 'Last 7 Days') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item py-1 small" href="{{ route('admin.dashboard', ['period' => '7_days']) }}">Last 7 Days</a></li>
                                <li><a class="dropdown-item py-1 small" href="{{ route('admin.dashboard', ['period' => '30_days']) }}">Last 30 Days</a></li>
                                <li><a class="dropdown-item py-1 small" href="{{ route('admin.dashboard', ['period' => 'this_month']) }}">This Month</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="parkingActivityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 6. SLOT STATUS DONUT -->
        <div class="col-12 col-lg-4">
            <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.15rem;">Slot Status</h5>
                
                <div class="row align-items-center">
                    <div class="col-7 position-relative d-flex justify-content-center">
                        <div style="width: 170px; height: 170px;">
                            <canvas id="slotStatusDonutChart"></canvas>
                        </div>
                        <!-- Center Total Badge -->
                        <div class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none;">
                            <div class="fw-extrabold text-dark" style="font-size: 1.6rem; line-height: 1;">{{ number_format($slotDistribution['total']) }}</div>
                            <div class="text-muted" style="font-size: 0.72rem; font-weight: 500;">Total Slots</div>
                        </div>
                    </div>

                    <div class="col-5 ps-0">
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #10B981;"></span>
                                    <span class="text-secondary fw-medium">Available</span>
                                </div>
                                <span class="fw-bold text-dark">{{ number_format($slotDistribution['available']) }}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: var(--sp-yellow);"></span>
                                    <span class="text-secondary fw-medium">Occupied</span>
                                </div>
                                <span class="fw-bold text-dark">{{ number_format($slotDistribution['occupied']) }}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #9CA3AF;"></span>
                                    <span class="text-secondary fw-medium">Maintenance</span>
                                </div>
                                <span class="fw-bold text-dark">{{ number_format($slotDistribution['maintenance']) }}</span>
                            </li>
                            @if(($slotDistribution['reserved'] ?? 0) > 0)
                                <li class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #3B82F6;"></span>
                                        <span class="text-secondary fw-medium">Reserved</span>
                                    </div>
                                    <span class="fw-bold text-dark">{{ number_format($slotDistribution['reserved']) }}</span>
                                </li>
                            @endif
                            @if(($slotDistribution['blocked'] ?? 0) > 0)
                                <li class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #374151;"></span>
                                        <span class="text-secondary fw-medium">Blocked</span>
                                    </div>
                                    <span class="fw-bold text-dark">{{ number_format($slotDistribution['blocked']) }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. RECENT BOOKINGS TABLE -->
    <div class="card-custom mb-4">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white rounded-top-3">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Recent Bookings</h5>
            <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none fw-semibold text-secondary small d-flex align-items-center gap-1">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="bg-light text-secondary" style="background-color: #F8FAFC !important; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
                    <tr>
                        <th class="ps-4 py-3 border-0">#</th>
                        <th class="py-3 border-0">User Name</th>
                        <th class="py-3 border-0">Vehicle No.</th>
                        <th class="py-3 border-0">Parking Location</th>
                        <th class="py-3 border-0">Entry Time</th>
                        <th class="py-3 border-0">Exit Time</th>
                        <th class="pe-4 py-3 border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $index => $b)
                        <tr>
                            <td class="ps-4 py-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                            <td class="py-3 fw-semibold text-dark">{{ $b->user->name ?? 'Guest User' }}</td>
                            <td class="py-3 text-secondary">{{ $b->vehicle->vehicle_number ?? ($b->vehicle_number ?? '-') }}</td>
                            <td class="py-3 text-secondary">{{ $b->parkingLocation->name ?? '-' }}</td>
                            <td class="py-3 text-secondary">
                                {{ optional($b->parkingEntry?->entry_time)->format('d M Y, h:i A') ?? optional($b->entry_time)->format('d M Y, h:i A') ?? '-' }}
                            </td>
                            <td class="py-3 text-secondary">
                                {{ optional($b->parkingExit?->exit_time)->format('d M Y, h:i A') ?? optional($b->actual_exit_time)->format('d M Y, h:i A') ?? '-' }}
                            </td>
                            <td class="pe-4 py-3 text-center">
                                @if($b->booking_status === 'ACTIVE' || $b->booking_status === 'CONFIRMED')
                                    <span class="badge-status-active">Active</span>
                                @elseif($b->booking_status === 'COMPLETED')
                                    <span class="badge-status-exited">Exited</span>
                                @else
                                    <span class="badge bg-light text-secondary border px-2 py-1">{{ $b->booking_status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No recent bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 8. QUICK ACTIONS -->
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem;">Quick Actions</h5>
        <div class="row g-3">
            <!-- Action 1: Add Location -->
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('admin.parking-locations.create') }}" class="text-decoration-none">
                    <div class="card-custom p-3 d-flex align-items-center justify-content-between hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="yellow-icon-badge me-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">Add Location</span>
                        </div>
                        <i class="bi bi-arrow-right text-secondary fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- Action 2: Manage Slots -->
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('admin.parking-slots.index') }}" class="text-decoration-none">
                    <div class="card-custom p-3 d-flex align-items-center justify-content-between hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="yellow-icon-badge me-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="bi bi-p-circle-fill"></i>
                            </div>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">Manage Slots</span>
                        </div>
                        <i class="bi bi-arrow-right text-secondary fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- Action 3: Manage Users -->
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                    <div class="card-custom p-3 d-flex align-items-center justify-content-between hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="yellow-icon-badge me-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">Manage Users</span>
                        </div>
                        <i class="bi bi-arrow-right text-secondary fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- Action 4: View Reports -->
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('admin.reports') }}" class="text-decoration-none">
                    <div class="card-custom p-3 d-flex align-items-center justify-content-between hover-shadow">
                        <div class="d-flex align-items-center">
                            <div class="yellow-icon-badge me-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                            </div>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">View Reports</span>
                        </div>
                        <i class="bi bi-arrow-right text-secondary fs-5"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. PARKING ACTIVITY BAR CHART
    const activityLabels = {!! json_encode($activityChart['labels']) !!};
    const entriesData = {!! json_encode($activityChart['entries']) !!};
    const exitsData = {!! json_encode($activityChart['exits']) !!};

    const ctxActivity = document.getElementById('parkingActivityChart').getContext('2d');
    new Chart(ctxActivity, {
        type: 'bar',
        data: {
            labels: activityLabels,
            datasets: [
                {
                    label: 'Entries',
                    data: entriesData,
                    backgroundColor: '#F9B91E',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.5
                },
                {
                    label: 'Exits',
                    data: exitsData,
                    backgroundColor: '#101828',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9' },
                    ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });

    // 2. SLOT STATUS DONUT CHART
    const slotAvail = {{ $slotDistribution['available'] }};
    const slotOcc = {{ $slotDistribution['occupied'] }};
    const slotMaint = {{ $slotDistribution['maintenance'] }};
    const slotRes = {{ $slotDistribution['reserved'] ?? 0 }};
    const slotBlock = {{ $slotDistribution['blocked'] ?? 0 }};

    const donutLabels = ['Available', 'Occupied', 'Maintenance'];
    const donutData = [slotAvail, slotOcc, slotMaint];
    const donutColors = ['#10B981', '#F9B91E', '#9CA3AF'];

    if (slotRes > 0) {
        donutLabels.push('Reserved');
        donutData.push(slotRes);
        donutColors.push('#3B82F6');
    }
    if (slotBlock > 0) {
        donutLabels.push('Blocked');
        donutData.push(slotBlock);
        donutColors.push('#374151');
    }

    const ctxDonut = document.getElementById('slotStatusDonutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: donutLabels,
            datasets: [{
                data: donutData,
                backgroundColor: donutColors,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
});
</script>
@endpush
