@foreach($types as $key => $type)
@if($key !== 'other' || $stats[$key]['total'] > 0)
<a class="summary-card {{ request('type', 'car') === $key ? 'highlighted' : '' }}" href="{{ route('admin.parking-slots.index', array_merge(request()->except(['page', 'type']), ['location_id' => $locationId, 'type' => request('type') === $key ? null : $key])) }}" aria-label="Filter {{ $type['label'] }}: {{ $stats[$key]['available'] }} available of {{ $stats[$key]['total'] }}">
    <i class="bi bi-{{ $type['icon'] }}" aria-hidden="true"></i><div><span>{{ $type['label'] }}</span><strong>{{ $stats[$key]['available'] }} / {{ $stats[$key]['total'] }}</strong><small>Available</small></div>
</a>
@endif
@endforeach
