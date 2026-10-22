<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Requirement #40: Staff Management (/admin/staff).
     */
    public function index(): View
    {
        $staffMembers = Staff::with('parkingLocation')->paginate(15);
        $locations = ParkingLocation::where('status', 'Active')->get();

        return view('admin.staff.index', compact('staffMembers', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:Parking Manager,Entry Staff,Exit Staff'],
            'parking_location_id' => ['nullable', 'exists:parking_locations,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['password'] = Hash::make($data['password']);

        Staff::create($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff account created successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $staff = Staff::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('staff')->ignore($staff->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:Parking Manager,Entry Staff,Exit Staff'],
            'parking_location_id' => ['nullable', 'exists:parking_locations,id'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated.');
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $staff = Staff::findOrFail($id);
        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update(['status' => $newStatus]);

        return back()->with('success', "Staff status changed to {$newStatus}.");
    }
}
