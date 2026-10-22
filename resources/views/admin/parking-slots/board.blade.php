@forelse($groupedSlots as $floor => $floorSlots)
<section class="floor-panel" aria-label="Slots - {{ $floor }}"><header><h2>Slots - {{ $floor }}</h2><div class="legend"><span><i class="dot available"></i>Available</span><span><i class="dot occupied"></i>Occupied</span><span><i class="dot reserved"></i>Reserved</span><span><i class="dot maintenance"></i>Total Slots: {{ $floorTotals[$floor] }}</span>@if($floorSlots->count() !== $floorTotals[$floor])<span class="filtered-count">Showing: {{ $floorSlots->count() }}</span>@endif</div></header>
    <div class="slot-grid">@foreach($floorSlots as $slot)
    @php
        $category = \App\Support\ParkingSlotBoard::category($slot);
        $type = $types[$category];
        $status = strtolower($slot->status);
        $icon = match ($slot->status) { 'MAINTENANCE' => 'wrench-adjustable', 'BLOCKED' => 'slash-circle', default => $category === 'car' ? 'car-front' : $type['icon'] };
        $payload = ['id' => $slot->id, 'slot_number' => $slot->slot_number, 'zone_id' => $slot->zone_id, 'slot_type' => $slot->slot_type ?? 'Standard', 'price' => $slot->price, 'floor' => $slot->floor, 'section' => $slot->section, 'description' => $slot->description, 'status' => $slot->status, 'update_url' => route('admin.parking-slots.update', $slot->id), 'delete_url' => route('admin.parking-slots.destroy', $slot->id)];
    @endphp
    <button type="button" class="slot-card {{ $status }}" data-slot="{{ json_encode($payload) }}" aria-label="Edit slot {{ $slot->slot_number }}, {{ ucfirst($status) }}, {{ $type['single'] }}" title="{{ $slot->zone?->zone_name }} · Click to manage">
        <span class="slot-top"><strong>{{ $slot->slot_number }}</strong><i class="dot {{ $status }}" aria-hidden="true"></i></span>
        <i class="slot-icon bi bi-{{ $icon }}" aria-hidden="true"></i><span class="status-badge">@if($status === 'reserved')<i class="bi bi-clock" aria-hidden="true"></i>@endif{{ ucfirst($status) }}</span><small>{{ $category === 'other' ? $slot->vehicle_type.' Slot' : $type['single'] }}</small>
    </button>
    @endforeach</div>
</section>
@empty
<div class="empty-state"><i class="bi bi-p-square" aria-hidden="true"></i><h2>{{ $selectedLocation ? 'No parking slots found' : 'No parking locations yet' }}</h2><p>{{ $selectedLocation ? 'Try another filter, or add slots for this location.' : 'Create a parking location and zone to start managing slots.' }}</p><a href="{{ $selectedLocation ? route('admin.parking-slots.index', ['location_id' => $locationId]) : route('admin.parking-locations.index') }}">{{ $selectedLocation ? 'Reset filters' : 'Manage parking locations' }}</a></div>
@endforelse
