<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\ParkingService;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageCmsController extends Controller
{
    /**
     * Display the Homepage CMS management panel.
     */
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $servicesCount = ParkingService::count();
        $locationsCount = ParkingLocation::count();

        return view('admin.homepage.index', compact('settings', 'servicesCount', 'locationsCount'));
    }

    /**
     * Update homepage settings from CMS panel.
     */
    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::set($key, is_null($value) ? '' : (string) $value, 'homepage');
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Home Page content successfully updated and live!');
    }
}
