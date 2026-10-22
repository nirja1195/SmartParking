<?php

namespace App\Http\Controllers;

use App\Models\ParkingService;
use App\Models\ServicePageFeature;
use App\Models\ServicePageSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesPageController extends Controller
{
    /**
     * Display the public Services page.
     */
    public function index(Request $request): View
    {
        $settings = ServicePageSetting::getSettings();
        $services = ParkingService::active()->get();
        $features = ServicePageFeature::active()->get();

        return view('services', compact('settings', 'services', 'features'));
    }

    /**
     * Display single service detail page.
     */
    public function show(string $slug): View
    {
        $service = ParkingService::where('slug', $slug)->firstOrFail();
        $settings = ServicePageSetting::getSettings();
        $features = ServicePageFeature::active()->get();
        $relatedServices = ParkingService::active()->where('id', '!=', $service->id)->take(3)->get();

        return view('service-detail', compact('service', 'settings', 'features', 'relatedServices'));
    }
}
