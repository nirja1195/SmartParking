<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Booking;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Requirement #25: Payment Page (/payment/{booking}).
     */
    public function show(int $bookingId, Request $request): View|RedirectResponse
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $request->user()->id)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle'])
            ->firstOrFail();

        if ($booking->payment_status === Booking::PAYMENT_PAID) {
            return redirect()->route('user.booking.success', $booking->id);
        }

        return view('user.payment', compact('booking'));
    }

    /**
     * Process Simulated Payment.
     */
    public function process(ProcessPaymentRequest $request, int $bookingId): RedirectResponse
    {
        try {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $paymentMethod = $request->input('payment_method');

            $this->paymentService->processPayment($booking, $paymentMethod);

            return redirect()->route('user.booking.success', $booking->id)
                ->with('success', 'Payment successful! Your parking slot is now reserved.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Requirement #26: Payment Success Page (/booking/success/{booking}).
     */
    public function success(int $bookingId, Request $request): View
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $request->user()->id)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle', 'payment'])
            ->firstOrFail();

        return view('user.booking-success', compact('booking'));
    }
}
