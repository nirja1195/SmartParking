<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Services\ParkingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParkingSlotController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    /**
     * Requirement #16: Parking Slots Management & Visual Grid.
     */
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $filters = $request->validate([
            'location_id' => ['nullable', 'integer', 'exists:parking_locations,id'],
            'zone_id' => ['nullable', 'integer', 'exists:parking_zones,id'],
            'type' => ['nullable', 'in:car,bike,ev,handicap,other'],
            'status' => ['nullable', 'in:AVAILABLE,RESERVED,OCCUPIED,MAINTENANCE,BLOCKED'],
            'floor' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:80'],
        ]);
        $locations = ParkingLocation::orderByRaw("CASE WHEN status = 'Active' THEN 0 ELSE 1 END")->orderBy('id')->get();
        $selectedLocation = !empty($filters['location_id']) ? $locations->firstWhere('id', (int) $filters['location_id']) : $locations->first();
        $locationId = $selectedLocation?->id;
        $zones = ParkingZone::with('parkingLocation')->where('parking_location_id', $locationId)->get();
        $allSlots = ParkingSlot::with(['parkingLocation', 'zone'])->where('parking_location_id', $locationId)->get();
        $types = \App\Support\ParkingSlotBoard::types();
        $stats = collect($types)->map(function ($type, $key) use ($allSlots) {
            $matching = $allSlots->filter(fn ($slot) => \App\Support\ParkingSlotBoard::category($slot) === $key);
            return ['total' => $matching->count(), 'available' => $matching->where('status', 'AVAILABLE')->count()];
        })->all();
        $floorTotals = $allSlots->groupBy(fn ($slot) => \App\Support\ParkingSlotBoard::floor($slot))->map->count();
        $floors = $floorTotals->keys()->sort(function ($a, $b) {
            return (\App\Support\ParkingSlotBoard::floorRank($a) <=> \App\Support\ParkingSlotBoard::floorRank($b)) ?: strnatcasecmp($a, $b);
        })->values();
        $slots = $allSlots->filter(function ($slot) use ($filters) {
            return (empty($filters['zone_id']) || $slot->zone_id == $filters['zone_id'])
                && (empty($filters['type']) || \App\Support\ParkingSlotBoard::category($slot) === $filters['type'])
                && (empty($filters['status']) || $slot->status === $filters['status'])
                && (empty($filters['floor']) || \App\Support\ParkingSlotBoard::floor($slot) === $filters['floor'])
                && ((!isset($filters['search']) || trim($filters['search']) === '') || str_contains(strtolower($slot->slot_number), strtolower(trim($filters['search']))));
        })->sort(fn ($a, $b) => strnatcasecmp($a->slot_number, $b->slot_number) ?: $a->id <=> $b->id);
        $groupedSlots = $slots->groupBy(fn ($slot) => \App\Support\ParkingSlotBoard::floor($slot))->sortKeysUsing(function ($a, $b) {
            return (\App\Support\ParkingSlotBoard::floorRank($a) <=> \App\Support\ParkingSlotBoard::floorRank($b)) ?: strnatcasecmp($a, $b);
        });
        $data = compact('slots', 'locations', 'zones', 'selectedLocation', 'locationId', 'types', 'stats', 'floors', 'floorTotals', 'groupedSlots');
        if ($request->expectsJson()) return response()->json([
            'floors' => $floors->all(),
            'summary' => view('admin.parking-slots.summary', $data)->render(),
            'board' => view('admin.parking-slots.board', $data)->render(),
        ]);
        $notifications = \App\Models\Notification::where('admin_id', auth('admin')->id())->latest()->limit(8)->get();
        $unreadCount = \App\Models\Notification::where('admin_id', auth('admin')->id())->where('is_read', false)->count();
        $branding = \App\Support\AdminLoginContent::current();
        return view('admin.parking-slots.index', array_merge($data, compact('notifications', 'unreadCount', 'branding')));
    }
    /**
     * Create individual parking slot.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge(['slot_number' => strtoupper(trim((string) $request->input('slot_number')))]);
        $data = $request->validate([
            'zone_id' => ['required', 'exists:parking_zones,id'],
            'slot_number' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('parking_slots')->where('zone_id', $request->input('zone_id'))],
            'slot_type' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'floor' => ['nullable', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:AVAILABLE,RESERVED,OCCUPIED,MAINTENANCE,BLOCKED'],
        ]);

        $zone = ParkingZone::findOrFail($data['zone_id']);

        $slot = ParkingSlot::create([
            'parking_location_id' => $zone->parking_location_id,
            'zone_id' => $zone->id,
            'slot_number' => strtoupper(trim($data['slot_number'])),
            'vehicle_type' => $zone->vehicle_type,
            'slot_type' => $data['slot_type'] ?? 'Standard',
            'price' => $data['price'] ?? null,
            'floor' => $data['floor'] ?? $zone->floor,
            'section' => $data['section'] ?? $zone->section,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return back()->with('success', "Parking slot {$slot->slot_number} created successfully.");
    }

    /**
     * Update individual parking slot details.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->merge(['slot_number' => strtoupper(trim((string) $request->input('slot_number')))]);
        $slot = ParkingSlot::findOrFail($id);

        $data = $request->validate([
            'slot_number' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('parking_slots')->where('zone_id', $slot->zone_id)->ignore($slot->id)],
            'slot_type' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'floor' => ['nullable', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:AVAILABLE,RESERVED,OCCUPIED,MAINTENANCE,BLOCKED'],
        ]);

        $slot->update([
            'slot_number' => strtoupper(trim($data['slot_number'])),
            'slot_type' => $data['slot_type'] ?? 'Standard',
            'price' => $data['price'] ?? null,
            'floor' => $data['floor'] ?? $slot->floor,
            'section' => $data['section'] ?? $slot->section,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return back()->with('success', "Parking slot {$slot->slot_number} updated successfully.");
    }

    /**
     * Batch create slots (e.g. A01..A05).
     */
    public function batchStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'zone_id' => ['required', 'exists:parking_zones,id'],
            'prefix' => ['required', 'string', 'max:5'],
            'start' => ['required', 'integer', 'min:1'],
            'count' => ['required', 'integer', 'min:1', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
        ]);

        $zone = ParkingZone::findOrFail($data['zone_id']);

        $created = $this->parkingService->batchCreateSlots(
            $zone,
            $data['prefix'],
            (int) $data['start'],
            (int) $data['count'],
            $data['floor'] ?? null,
            $data['section'] ?? null
        );

        return back()->with('success', "Generated {$created} new parking slots for {$zone->zone_name}.");
    }

    /**
     * Update individual slot status.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:AVAILABLE,RESERVED,OCCUPIED,MAINTENANCE,BLOCKED'],
        ]);

        $this->parkingService->updateSlotStatus($id, $request->input('status'));

        return back()->with('success', 'Slot status updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        if (ParkingSlot::findOrFail($id)->bookings()->exists()) {
            return back()->withErrors(['slot' => 'This slot has booking history and cannot be deleted. Mark it Blocked or Maintenance instead.']);
        }
        $this->parkingService->deleteSlot($id);

        return back()->with('success', 'Parking slot removed.');
    }
}
