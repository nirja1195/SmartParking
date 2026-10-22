<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreParkingLotRequest;
use App\Http\Requests\Admin\UpdateParkingLotRequest;
use App\Models\ParkingLot;
use App\Services\ParkingLotService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParkingLotController extends Controller
{
    protected ParkingLotService $lotService;

    public function __construct(ParkingLotService $lotService)
    {
        $this->lotService = $lotService;
    }

    public function index(): View
    {
        $lots = $this->lotService->getActiveLots();

        return view('admin.parking-lots.index', compact('lots'));
    }

    public function create(): View
    {
        return view('admin.parking-lots.create');
    }

    public function store(StoreParkingLotRequest $request): RedirectResponse
    {
        try {
            $floors = $request->input('floors', ['Ground', 'Floor 1']);
            $this->lotService->createParkingLot($request->validated(), $floors);

            return redirect()->route('admin.parking-lots.index')
                ->with('success', 'Parking lot and initial spots created successfully.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Failed to create lot: '.$e->getMessage());
        }
    }

    public function show(int $id): View
    {
        $lot = $this->lotService->getLotById($id);
        if (! $lot) {
            abort(404, 'Parking lot not found.');
        }

        return view('admin.parking-lots.show', compact('lot'));
    }

    public function edit(int $id): View
    {
        $lot = ParkingLot::findOrFail($id);

        return view('admin.parking-lots.edit', compact('lot'));
    }

    public function update(UpdateParkingLotRequest $request, int $id): RedirectResponse
    {
        try {
            $this->lotService->updateParkingLot($id, $request->validated());

            return redirect()->route('admin.parking-lots.index')
                ->with('success', 'Parking lot details updated successfully.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Update failed: '.$e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->lotService->deleteParkingLot($id);

            return redirect()->route('admin.parking-lots.index')
                ->with('success', 'Parking lot removed.');
        } catch (Exception $e) {
            return back()->with('error', 'Unable to delete parking lot: '.$e->getMessage());
        }
    }
}
