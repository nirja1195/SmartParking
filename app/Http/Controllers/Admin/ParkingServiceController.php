<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingService;
use App\Models\ServicePageFeature;
use App\Models\ServicePageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParkingServiceController extends Controller
{
    /**
     * Display a listing of the parking services and CMS management.
     */
    public function index(): View
    {
        $services = ParkingService::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $features = ServicePageFeature::orderBy('sort_order', 'asc')->get();
        $settings = ServicePageSetting::getSettings();

        return view('admin.parking-services.index', compact('services', 'features', 'settings'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150|unique:parking_services,slug',
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle uploaded image file
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/services');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['image'] = 'uploads/services/' . $fileName;
        }

        if (empty($validated['image'])) {
            $validated['image'] = 'images/services/card-car.png';
        }

        ParkingService::create($validated);

        return redirect()->route('admin.parking-services.index')->with('success', 'Parking service successfully created.');
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $parking_service = $id instanceof ParkingService ? $id : ParkingService::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150|unique:parking_services,slug,' . $parking_service->id,
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle uploaded image file
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/services');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);

            // Clean up old custom file if it was in uploads/services
            if ($parking_service->image && str_starts_with($parking_service->image, 'uploads/services/')) {
                $oldPath = public_path($parking_service->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $validated['image'] = 'uploads/services/' . $fileName;
        }

        $parking_service->update($validated);

        return redirect()->route('admin.parking-services.index')->with('success', 'Parking service successfully updated.');
    }

    /**
     * Remove the specified service.
     */
    public function destroy($id): RedirectResponse
    {
        $parking_service = $id instanceof ParkingService ? $id : ParkingService::findOrFail($id);

        if ($parking_service->image && str_starts_with($parking_service->image, 'uploads/services/')) {
            $oldPath = public_path($parking_service->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $parking_service->delete();

        return redirect()->route('admin.parking-services.index')->with('success', 'Parking service removed.');
    }

    /**
     * Toggle service status.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $service = ParkingService::findOrFail($id);
        $service->status = $service->status === 'Active' ? 'Inactive' : 'Active';
        $service->save();

        return back()->with('success', "Service '{$service->name}' status updated to {$service->status}.");
    }

    /**
     * Update Services Page general CMS settings (Hero, Banner, SEO).
     */
    public function updatePageSettings(Request $request): RedirectResponse
    {
        $settings = ServicePageSetting::getSettings();
        $validated = $request->validate([
            'hero_eyebrow' => 'required|string|max:100',
            'hero_title' => 'required|string|max:150',
            'hero_highlight' => 'required|string|max:150',
            'hero_description' => 'nullable|string|max:500',
            'hero_image' => 'nullable|string|max:255',
            'hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'services_section_title' => 'required|string|max:150',
            'services_section_description' => 'nullable|string|max:500',
            'search_placeholder' => 'nullable|string|max:100',
            'custom_banner_title' => 'required|string|max:200',
            'custom_banner_description' => 'nullable|string|max:500',
            'custom_banner_button_text' => 'required|string|max:100',
            'custom_banner_button_url' => 'nullable|string|max:255',
            'custom_banner_icon' => 'nullable|string|max:100',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $fileName = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/services');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['hero_image'] = 'uploads/services/' . $fileName;
        }

        $settings->update($validated);

        return redirect()->route('admin.parking-services.index')->with('success', 'Services Page settings successfully updated.');
    }

    /**
     * Store or update feature strip item.
     */
    public function storeFeature(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'icon' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        ServicePageFeature::create($validated);

        return redirect()->route('admin.parking-services.index')->with('success', 'Feature strip item added.');
    }

    /**
     * Update feature strip item.
     */
    public function updateFeature(Request $request, int $id): RedirectResponse
    {
        $feature = ServicePageFeature::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'icon' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        $feature->update($validated);

        return redirect()->route('admin.parking-services.index')->with('success', 'Feature strip item updated.');
    }

    /**
     * Delete feature strip item.
     */
    public function destroyFeature(int $id): RedirectResponse
    {
        $feature = ServicePageFeature::findOrFail($id);
        $feature->delete();

        return redirect()->route('admin.parking-services.index')->with('success', 'Feature strip item removed.');
    }
}
