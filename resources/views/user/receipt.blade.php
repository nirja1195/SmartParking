<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $booking->booking_id }} - SMARTPARK AI</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            padding: 2rem 1rem;
        }
        .receipt-card {
            background: #ffffff;
            border-radius: 1rem;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
        }
        .receipt-header {
            border-bottom: 2px dashed #cbd5e1;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                border: none;
                box-shadow: none;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <!-- Print / Action Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('user.booking.detail', $booking->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-sm btn-primary">
                    <i class="bi bi-printer me-1"></i> PRINT RECEIPT
                </button>
                <button onclick="window.print()" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-download me-1"></i> DOWNLOAD RECEIPT
                </button>
            </div>
        </div>

        <!-- Receipt Header (Requirement #32) -->
        <div class="receipt-header text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2 mb-2" style="width: 44px; height: 44px;">
                <i class="bi bi-p-square-fill fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-0">SMARTPARK AI</h3>
            <p class="text-muted small mb-2">Smart Parking Management System &bull; Official Digital Tax Invoice</p>
            <div class="badge bg-light text-dark border px-3 py-1 font-monospace fs-6">
                Booking ID: {{ $booking->booking_id }}
            </div>
        </div>

        <!-- Parameters Breakdown -->
        <div class="row g-3 small mb-4">
            <div class="col-6">
                <span class="text-muted d-block">Customer Name:</span>
                <strong>{{ $booking->user->name }}</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted d-block">Vehicle Number:</span>
                <strong class="font-monospace">{{ $booking->vehicle->vehicle_number ?? 'Registered Vehicle' }}</strong>
            </div>

            <div class="col-6">
                <span class="text-muted d-block">Parking Facility:</span>
                <strong>{{ $booking->parkingLocation->name }}</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted d-block">Zone & Assigned Slot:</span>
                <strong class="text-primary">{{ $booking->zone->zone_name }} &bull; Slot {{ $booking->slot->slot_number }}</strong>
            </div>

            <div class="col-6">
                <span class="text-muted d-block">Entry Time:</span>
                <strong>{{ $booking->entry_time->format('M d, Y - h:i A') }}</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted d-block">Exit Time:</span>
                <strong>{{ $booking->actual_exit_time ? $booking->actual_exit_time->format('M d, Y - h:i A') : $booking->expected_exit_time->format('M d, Y - h:i A') }}</strong>
            </div>

            <div class="col-6">
                <span class="text-muted d-block">Parking Duration:</span>
                <strong>{{ $booking->duration }} Hour(s)</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted d-block">Hourly Tariff:</span>
                <strong>${{ number_format($booking->parkingLocation->parking_charges, 2) }} / hr</strong>
            </div>
        </div>

        <hr class="my-3">

        <!-- Financial Breakdown -->
        <div class="mb-4">
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Payment Method:</span>
                <span class="fw-semibold">{{ $booking->payment->payment_method ?? 'Cashless' }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Transaction ID:</span>
                <span class="font-monospace">{{ $booking->payment->transaction_id ?? 'TXN-MOCK-PAID' }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Payment Status:</span>
                <span class="badge bg-success">PAID</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fs-5 fw-bold text-dark">Total Amount Paid:</span>
                <span class="fs-4 fw-bold text-success">${{ number_format($booking->amount, 2) }}</span>
            </div>
        </div>

        <div class="text-center pt-3 border-top text-muted small">
            Thank you for parking with SMARTPARK AI. Drive safely!
        </div>
    </div>
</body>
</html>
