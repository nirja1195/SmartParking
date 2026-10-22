@extends('layouts.admin')
@section('title', 'Users')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush
@section('content')
<section class="users-page">
<nav class="users-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span><span>Users</span></nav>
<header class="users-heading"><div><h1>Users</h1><p>Manage all users of the parking system. View, search, edit or add new users.</p></div><a class="user-new" href="{{ route('admin.users.create') }}"><i class="bi bi-plus-lg"></i> Add New User</a></header>
<div class="user-metrics">@foreach($metrics as $metric)<article class="user-metric"><i class="bi bi-{{ $metric['icon'] }} metric-icon {{ $metric['tone'] }}" aria-hidden="true"></i><div><span>{{ $metric['label'] }}</span><strong>{{ number_format($metric['count']) }}</strong><small title="New registrations this month compared with last month">@if($metric['change'] !== null)<b class="{{ $metric['change'] < 0 ? 'negative' : '' }}">{{ $metric['change'] >= 0 ? '+' : '' }}{{ $metric['change'] }}%</b> vs last month @else No previous month data @endif</small></div></article>@endforeach</div>
<form method="get" class="user-filters" action="{{ route('admin.users.index') }}" id="userFilters">
<label class="user-search"><i class="bi bi-search" aria-hidden="true"></i><input name="search" aria-label="Search users" placeholder="Search by name, email or phone..." value="{{ request('search') }}" maxlength="100"></label>
<select name="role" aria-label="Role"><option value="">All Roles</option><option value="user" @selected(request('role') === 'user')>User</option></select>
<select name="status" aria-label="Status"><option value="">All Status</option>@foreach(['active','inactive','blocked'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
<label class="user-joined"><i class="bi bi-calendar3" aria-hidden="true"></i><span>Joined Date</span><input type="date" name="joined_date" value="{{ request('joined_date') }}" aria-label="Joined date"></label>
<a class="user-reset" href="{{ route('admin.users.index') }}">Reset</a><noscript><button type="submit">Apply filters</button></noscript></form>
<div class="user-table-card"><div class="user-table-scroll"><table class="user-table"><thead><tr><th>#</th><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th>Bookings</th><th>Joined On</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($users as $user)
<tr><td>{{ $users->firstItem() + $loop->index }}</td><td><a class="user-identity" href="{{ route('admin.users.show', $user) }}"><span class="user-avatar">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span><span>{{ $user->name }}</span></a></td><td>{{ $user->email }}</td><td class="user-phone">{{ $user->phone ?: '—' }}</td><td><span class="user-role">User</span></td><td>{{ $user->bookings_count }}</td><td class="user-date">{{ $user->created_at?->format('d M Y') ?? '—' }}</td><td><button type="button" class="user-status {{ $user->status }}" data-bs-toggle="modal" data-bs-target="#statusUser{{ $user->id }}" aria-label="Change status for {{ $user->name }}">{{ ucfirst($user->status) }}</button></td><td><div class="user-actions"><a href="{{ route('admin.users.show', $user) }}" aria-label="View {{ $user->name }}" title="View user"><i class="bi bi-eye"></i></a><a href="{{ route('admin.users.edit', $user) }}" aria-label="Edit {{ $user->name }}" title="Edit user"><i class="bi bi-pencil"></i></a><button type="button" class="delete-user" data-bs-toggle="modal" data-bs-target="#deleteUser{{ $user->id }}" aria-label="Delete {{ $user->name }}" title="Delete user"><i class="bi bi-trash"></i></button></div></td></tr>
@empty
<tr><td colspan="9" class="user-empty"><i class="bi bi-people"></i><strong>No users found</strong><span>Users matching your filters will appear here.</span></td></tr>
@endforelse
</tbody></table></div>
<footer class="user-pagination"><span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ number_format($users->total()) }} users</span><nav aria-label="User pages"><a href="{{ $users->previousPageUrl() ?? '#' }}" @if($users->onFirstPage()) aria-disabled="true" tabindex="-1" @endif aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>@for($page = max(1, $users->currentPage() - 2); $page <= min($users->lastPage(), $users->currentPage() + 2); $page++)<a href="{{ $users->url($page) }}" @if($page === $users->currentPage()) class="current" aria-current="page" @endif>{{ $page }}</a>@endfor<a href="{{ $users->nextPageUrl() ?? '#' }}" @if(!$users->hasMorePages()) aria-disabled="true" tabindex="-1" @endif aria-label="Next page"><i class="bi bi-chevron-right"></i></a></nav></footer></div>
</section>
@foreach($users as $user)
<div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1" aria-labelledby="deleteTitle{{ $user->id }}" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="post" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')<div class="modal-header"><h2 class="modal-title fs-5" id="deleteTitle{{ $user->id }}">Delete User</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">Delete <strong>{{ $user->name }}</strong> ({{ $user->email }})? @if($user->bookings_count)<p class="text-danger small mt-3 mb-0">This user has {{ $user->bookings_count }} bookings. Deleting the account also removes its history. Users with active bookings cannot be deleted.</p>@endif</div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger" type="submit">Delete User</button></div></form></div></div></div>
<div class="modal fade" id="statusUser{{ $user->id }}" tabindex="-1" aria-labelledby="statusTitle{{ $user->id }}" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="post" action="{{ route('admin.users.status', $user) }}">@csrf @method('PATCH')<div class="modal-header"><h2 class="modal-title fs-5" id="statusTitle{{ $user->id }}">Update User Status</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><label class="form-label" for="status{{ $user->id }}">{{ $user->name }}</label><select class="form-select" name="status" id="status{{ $user->id }}">@foreach(['active','inactive','blocked'] as $status)<option value="{{ $status }}" @selected($user->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-warning" type="submit">Save Status</button></div></form></div></div></div>
@endforeach
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
 const filters = document.getElementById('userFilters');
 filters.querySelectorAll('select,input[type="date"]').forEach(input => input.addEventListener('change', () => filters.requestSubmit()));
 let timer;
 filters.elements.search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => filters.requestSubmit(), 650); });
 filters.addEventListener('submit', () => clearTimeout(timer));
});
</script>
@endpush