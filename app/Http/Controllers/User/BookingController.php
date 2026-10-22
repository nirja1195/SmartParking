<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Models\Booking;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Vehicle;
use App\Services\BookingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Requirement #24: Booking Review (/booking/review).
     */
    public function review(Request $request): View|RedirectResponse
    {
        $slotId = $request->input('slot_id');
        $locationId = $request->input('parking_location_id');
        $zoneId = $request->input('zone_id');
        $vehicleId = $request->input('vehicle_id');
        $bookingDate = $request->input('booking_date', date('Y-m-d'));
        $entryTimeStr = $request->input('entry_time');
        $exitTimeStr = $request->input('expected_exit_time');

        if (! $slotId || ! $locationId || ! $zoneId || ! $entryTimeStr || ! $exitTimeStr) {
            return redirect()->route('user.find-parking')
                ->with('error', 'Please select location, zone, slot, and time interval to review booking.');
        }

        $location = ParkingLocation::findOrFail($locationId);
        $zone = ParkingZone::findOrFail($zoneId);
        $slot = ParkingSlot::findOrFail($slotId);
        $vehicle = $vehicleId ? Vehicle::find($vehicleId) : null;

        $entryTime = Carbon::parse($entryTimeStr);
        $exitTime = Carbon::parse($exitTimeStr);

        $durationMinutes = max(60, $entryTime->diffInMinutes($exitTime));
        $durationHours = (int) ceil($durationMinutes / 60);
        $hourlyRate = (float) $location->parking_charges;
        $totalAmount = round($durationHours * $hourlyRate, 2);

        return view('user.booking-review', compact(
            'location',
            'zone',
            'slot',
            'vehicle',
            'bookingDate',
            'entryTime',
            'exitTime',
            'durationHours',
            'hourlyRate',
            'totalAmount'
        ));
    }

    /**
     * Requirement #21: Create pending booking and redirect to payment.
     */
    public function store(CreateBookingRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $booking = $this->bookingService->createBooking($data);

            return redirect()->route('user.payment.show', $booking->id);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Requirement #27: My Bookings (/my-bookings).
     */
    public function myBookings(Request $request): View
    {
        $tab = $request->query('tab', 'all');
        $bookings = $this->bookingService->getUserBookings($request->user()->id, $tab, 10);

        return view('user.my-bookings', compact('bookings', 'tab'));
    }

    /**
     * Requirement #28: Booking Details (/my-bookings/{id}).
     */
    public function show(int $id, Request $request): View
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle', 'payment', 'parkingEntry', 'parkingExit'])
            ->firstOrFail();

        return view('user.booking-detail', compact('booking'));
    }

    /**
     * Requirement #32: Digital Printable Receipt.
     */
    public function receipt(int $id, Request $request): View
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle', 'payment', 'parkingEntry', 'parkingExit'])
            ->firstOrFail();

        return view('user.receipt', compact('booking'));
    }

    /**
     * Cancel booking.
     */
    public function cancel(int $id, Request $request): RedirectResponse
    {
        try {
            $this->bookingService->cancelBooking($id, $request->user()->id);

            return redirect()->route('user.my-bookings')
                ->with('success', 'Booking cancelled successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
