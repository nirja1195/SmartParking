<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GateActionRequest;
use App\Models\Booking;
use App\Models\ParkingExit;
use App\Services\GateService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExitController extends Controller
{
    protected GateService $gateService;

    public function __construct(GateService $gateService)
    {
        $this->gateService = $gateService;
    }

    /**
     * Requirement #31: Vehicle Exit Management (/admin/exits).
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
            ->where('booking_status', Booking::STATUS_ACTIVE)
            ->with(['user', 'parkingLocation', 'zone', 'slot', 'vehicle'])
            ->first();
        }

        // Recent exits list
        $recentExits = ParkingExit::with(['booking.user', 'slot.parkingLocation', 'staff'])
            ->orderBy('id', 'desc')
            ->limit(15)
            ->get();

        return view('admin.exits.index', compact('search', 'foundBooking', 'recentExits'));
    }

    /**
     * Confirm vehicle exit:
     * Slot: OCCUPIED -> AVAILABLE
     * Booking: ACTIVE -> COMPLETED
     */
    public function confirm(GateActionRequest $request): RedirectResponse
    {
        try {
            $identifier = $request->input('identifier');
            $remarks = $request->input('remarks');
            $staffId = Auth::guard('staff')->id() ?? null;

            $exit = $this->gateService->confirmExit($identifier, $staffId, $remarks);

            $msg = "Vehicle Exit Confirmed for Booking #{$exit->booking->booking_id}. Slot {$exit->slot->slot_number} is now AVAILABLE.";
            if ($exit->penalty_charge > 0) {
                $msg .= " Overtime penalty applied: \${$exit->penalty_charge}.";
            }

            $redirectRoute = Auth::guard('staff')->check() ? 'staff.exits.index' : 'admin.exits.index';
            return redirect()->route($redirectRoute)->with('success', $msg);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
