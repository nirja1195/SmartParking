<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreBookingRequest;
use App\Models\Booking;
use App\Models\ParkingLot;
use App\Models\ParkingSpot;
use App\Services\BookingService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;

    public function __construct(BookingService $bookingService, PaymentService $paymentService)
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
    }

    public function index(Request $request): View
    {
        $bookings = $this->bookingService->getUserBookings($request->user()->id, 10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create(Request $request): View
    {
        $lots = ParkingLot::where('is_active', true)->with(['spots' => fn ($q) => $q->where('is_active', true)])->get();
        $vehicles = $request->user()->vehicles;

        return view('customer.bookings.create', compact('lots', 'vehicles'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $booking = $this->bookingService->createBooking($data);

            // Mock automatic payment settlement for the demo flow
            $this->paymentService->processPayment($booking, 'card', [
                'card_brand' => 'Visa',
                'last4' => '4242',
            ]);

            return redirect()->route('customer.bookings.show', $booking->booking_reference)
                ->with('success', "Booking confirmed successfully! Reference: {$booking->booking_reference}");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(string $reference): View
    {
        $booking = $this->bookingService->getBookingByReference($reference);

        if (! $booking || $booking->user_id !== auth()->id()) {
            abort(404, 'Booking not found.');
        }

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, string $reference): RedirectResponse
    {
        try {
            $booking = Booking::where('booking_reference', $reference)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $this->bookingService->cancelBooking($booking->booking_reference, 'Cancelled by user');

            return redirect()->route('customer.bookings.index')
                ->with('success', 'Your reservation has been cancelled.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
