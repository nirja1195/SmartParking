<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GateActionRequest;
use App\Models\Booking;
use App\Models\ParkingEntry;
use App\Services\GateService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EntryController extends Controller
{
    protected GateService $gateService;

    public function __construct(GateService $gateService)
    {
        $this->gateService = $gateService;
    }

    /**
     * Requirement #29: Vehicle Entry Management (/admin/entries).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $foundBooking = null;

        if ($search) {
            $foundBooking = Booking::where(function ($q) use ($search) {
                $q->where('booking_id', $search)
                    ->orWhereHas('vehicle', function ($v) use ($search) {
                        $v->where('vehicle_number', $search);
                    });
            })
            ->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_PENDING])
            ->with(['user', 'parkingLocation', 'zone', 'slot', 'vehicle'])
            ->first();
        }

        // Recent entries list
        $recentEntries = ParkingEntry::with(['booking.user', 'vehicle', 'slot.parkingLocation', 'staff'])
            ->orderBy('id', 'desc')
            ->limit(15)
            ->get();

        return view('admin.entries.index', compact('search', 'foundBooking', 'recentEntries'));
    }

    /**
     * Confirm vehicle entry:
     * Slot: RESERVED -> OCCUPIED
     * Booking: CONFIRMED -> ACTIVE
     */
    public function confirm(GateActionRequest $request): RedirectResponse
    {
        try {
            $identifier = $request->input('identifier');
            $remarks = $request->input('remarks');
            $staffId = Auth::guard('staff')->id() ?? null;

            $entry = $this->gateService->confirmEntry($identifier, $staffId, $remarks);

            $redirectRoute = Auth::guard('staff')->check() ? 'staff.entries.index' : 'admin.entries.index';
            return redirect()->route($redirectRoute)
                ->with('success', "Vehicle Entry Confirmed for Booking #{$entry->booking->booking_id}. Slot {$entry->slot->slot_number} is now OCCUPIED.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
