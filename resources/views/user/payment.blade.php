@extends('layouts.user')

@section('title', 'Payment - Booking #'.$booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Simulated Payment Checkout</h4>
                    <p class="text-muted small mb-0">Booking Reference: <span class="font-monospace fw-bold">#{{ $booking->booking_id }}</span></p>
                </div>
                <span class="badge bg-primary fs-6 px-3 py-2">
                    Pay ${{ number_format($booking->amount, 2) }}
                </span>
            </div>

            <!-- Booking Summary Card -->
            <div class="p-3 bg-light rounded border mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-card-checklist me-1"></i> Booking Summary</h6>
                <div class="row g-2 small">
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Facility</span>
                        <strong>{{ $booking->parkingLocation->name }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Zone & Spot</span>
                        <strong>{{ $booking->zone->zone_name }} &bull; Slot {{ $booking->slot->slot_number }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Vehicle</span>
                        <strong>{{ $booking->vehicle->vehicle_number ?? 'Customer Vehicle' }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Date</span>
                        <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Scheduled Entry</span>
                        <strong>{{ $booking->entry_time->format('h:i A') }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <span class="text-muted d-block">Total Amount</span>
                        <strong class="text-success fs-6">${{ number_format($booking->amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Options Form (Requirement #25: UPI, Debit Card, Credit Card, Net Banking, Wallet) -->
            <form method="POST" action="{{ route('user.payment.process', $booking->id) }}">
                @csrf

                <h5 class="fw-bold text-dark mb-3">Select Payment Method</h5>

                <div class="row g-3 mb-4">
                    <!-- UPI -->
                    <div class="col-md-6">
                        <label class="card p-3 border cursor-pointer h-100 payment-option" for="pay_upi">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_upi" value="UPI" checked>
                                <div>
                                    <strong class="d-block text-dark"><i class="bi bi-phone me-1 text-primary"></i> UPI (Instant Pay)</strong>
                                    <small class="text-muted">Google Pay, PhonePe, Paytm, BHIM</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Debit Card -->
                    <div class="col-md-6">
                        <label class="card p-3 border cursor-pointer h-100 payment-option" for="pay_debit">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_debit" value="Debit Card">
                                <div>
                                    <strong class="d-block text-dark"><i class="bi bi-credit-card-2-front me-1 text-success"></i> Debit Card</strong>
                                    <small class="text-muted">Visa, MasterCard, RuPay</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Credit Card -->
                    <div class="col-md-6">
                        <label class="card p-3 border cursor-pointer h-100 payment-option" for="pay_credit">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_credit" value="Credit Card">
                                <div>
                                    <strong class="d-block text-dark"><i class="bi bi-credit-card me-1 text-info"></i> Credit Card</strong>
                                    <small class="text-muted">All major credit card issuers</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Net Banking -->
                    <div class="col-md-6">
                        <label class="card p-3 border cursor-pointer h-100 payment-option" for="pay_netbanking">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_netbanking" value="Net Banking">
                                <div>
                                    <strong class="d-block text-dark"><i class="bi bi-bank me-1 text-secondary"></i> Net Banking</strong>
                                    <small class="text-muted">All Indian & International banks</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Wallet -->
                    <div class="col-12">
                        <label class="card p-3 border cursor-pointer payment-option" for="pay_wallet">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="pay_wallet" value="Wallet">
                                <div>
                                    <strong class="d-block text-dark"><i class="bi bi-wallet2 me-1 text-warning"></i> Digital Wallet</strong>
                                    <small class="text-muted">SmartPark Balance, Amazon Pay, Mobikwik</small>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="alert alert-info small d-flex align-items-center">
                    <i class="bi bi-shield-check fs-4 me-2"></i>
                    <div>
                        <strong>Demo Environment Mode:</strong> Transaction is simulated securely for college project evaluation without external gateway charges.
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('user.find-parking') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                        CONFIRM & PAY ${{ number_format($booking->amount, 2) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
