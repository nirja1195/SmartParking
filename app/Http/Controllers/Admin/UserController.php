<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Requirement #39: Admin User Management (/admin/users).
     */
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive,blocked'],
            'role' => ['nullable', 'in:user'],
            'joined_date' => ['nullable', 'date'],
        ]);
        $query = User::withCount(['vehicles', 'bookings'])->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('joined_date')) $query->whereDate('created_at', $request->joined_date);
        $users = $query->paginate(10)->withQueryString();
        $metrics = [
            ['label'=>'Total Users','icon'=>'people-fill','tone'=>'gold','count'=>User::count()],
            ['label'=>'Active Users','icon'=>'person-fill-check','tone'=>'green','count'=>User::where('status','active')->count()],
            ['label'=>'Inactive Users','icon'=>'person-fill-dash','tone'=>'red','count'=>User::where('status','inactive')->count()],
            ['label'=>'New Users (This Month)','icon'=>'shield-fill-check','tone'=>'blue','count'=>User::whereBetween('created_at',[now()->startOfMonth(),now()])->count()],
        ];
        foreach ($metrics as $index => &$metric) {
            $base = User::query();
            if ($index === 1) $base->where('status', 'active');
            if ($index === 2) $base->where('status', 'inactive');
            $current = (clone $base)->whereBetween('created_at', [now()->startOfMonth(), now()])->count();
            $previous = (clone $base)->whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])->count();
            $metric['change'] = $previous ? round(($current - $previous) / $previous * 100) : null;
        }
        unset($metric);

        return view('admin.users.index', compact('users', 'metrics'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive,blocked'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "Customer account for '{$user->name}' created successfully.");
    }

    public function show(int $id): View
    {
        $user = User::with([
            'vehicles',
            'bookings' => fn ($q) => $q->with(['parkingLocation', 'slot', 'payment'])->orderBy('id', 'desc')->limit(15),
        ])->findOrFail($id);

        $totalSpent = $user->bookings()->where('payment_status', 'PAID')->sum('amount');
        $activeBookingsCount = $user->bookings()->whereIn('booking_status', ['CONFIRMED', 'ACTIVE'])->count();

        return view('admin.users.show', compact('user', 'totalSpent', 'activeBookingsCount'));
    }

    public function edit(int $id): View
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive,blocked'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User '{$user->name}' profile updated successfully.");
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:active,inactive,blocked'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->input('status')]);

        return back()->with('success', "Customer '{$user->name}' status updated to {$user->status}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::withCount(['bookings as active_bookings_count' => function ($q) {
            $q->whereIn('booking_status', ['CONFIRMED', 'ACTIVE']);
        }])->findOrFail($id);

        if ($user->active_bookings_count > 0) {
            return back()->with('error', 'Cannot remove this customer because they have active parking bookings.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Customer account '{$userName}' removed.");
    }
}
