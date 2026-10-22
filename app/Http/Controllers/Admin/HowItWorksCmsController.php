<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HowItWorksCta;
use App\Models\HowItWorksCtaFeature;
use App\Models\HowItWorksPageSetting;
use App\Models\HowItWorksStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HowItWorksCmsController extends Controller
{
    /**
     * Display the CMS panel for the How It Works page.
     */
    public function index(): View
    {
        $settings = HowItWorksPageSetting::current();
        $steps = HowItWorksStep::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $cta = HowItWorksCta::current();
        $ctaFeatures = HowItWorksCtaFeature::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.how-it-works.index', compact('settings', 'steps', 'cta', 'ctaFeatures', 'faqs'));
    }

    /**
     * Update page settings & hero section.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $settings = HowItWorksPageSetting::current();

        $validated = $request->validate([
            'hero_eyebrow' => 'required|string|max:100',
            'hero_title' => 'required|string|max:150',
            'hero_highlight' => 'nullable|string|max:150',
            'hero_description' => 'nullable|string',
            'hero_image' => 'nullable|string|max:255',
            'hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'section_title' => 'nullable|string|max:150',
            'section_description' => 'nullable|string|max:255',
            'faq_eyebrow' => 'nullable|string|max:100',
            'faq_title' => 'nullable|string|max:150',
            'faq_highlight' => 'nullable|string|max:150',
            'faq_description' => 'nullable|string',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $fileName = 'hero_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/how-it-works');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['hero_image'] = 'uploads/how-it-works/' . $fileName;
        }

        $settings->update($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'settings'])->with('success', 'Page settings updated successfully.');
    }

    /**
     * Store a new step.
     */
    public function storeStep(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'step_number' => 'required|string|max:10',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'step_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/how-it-works');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['image'] = 'uploads/how-it-works/' . $fileName;
        }

        if (empty($validated['image'])) {
            $validated['image'] = 'images/how-it-works/step-01.png';
        }

        HowItWorksStep::create($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'steps'])->with('success', 'Step created successfully.');
    }

    /**
     * Update an existing step.
     */
    public function updateStep(Request $request, $id): RedirectResponse
    {
        $step = HowItWorksStep::findOrFail($id);

        $validated = $request->validate([
            'step_number' => 'required|string|max:10',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'step_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/how-it-works');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['image'] = 'uploads/how-it-works/' . $fileName;
        }

        $step->update($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'steps'])->with('success', 'Step updated successfully.');
    }

    /**
     * Delete a step.
     */
    public function destroyStep($id): RedirectResponse
    {
        $step = HowItWorksStep::findOrFail($id);
        $step->delete();

        return redirect()->route('admin.how-it-works.index', ['tab' => 'steps'])->with('success', 'Step deleted successfully.');
    }

    /**
     * Update CTA Banner settings.
     */
    public function updateCta(Request $request): RedirectResponse
    {
        $cta = HowItWorksCta::current();

        $validated = $request->validate([
            'eyebrow' => 'required|string|max:100',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'main_image' => 'nullable|string|max:255',
            'main_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'google_play_url' => 'nullable|string|max:255',
            'app_store_url' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('main_image_file')) {
            $file = $request->file('main_image_file');
            $fileName = 'cta_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/how-it-works');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $validated['main_image'] = 'uploads/how-it-works/' . $fileName;
        }

        $cta->update($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'cta'])->with('success', 'App CTA banner updated successfully.');
    }

    /**
     * Store a new CTA Feature.
     */
    public function storeCtaFeature(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        HowItWorksCtaFeature::create($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'cta'])->with('success', 'CTA Feature added successfully.');
    }

    /**
     * Update an existing CTA Feature.
     */
    public function updateCtaFeature(Request $request, $id): RedirectResponse
    {
        $feature = HowItWorksCtaFeature::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        $feature->update($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'cta'])->with('success', 'CTA Feature updated successfully.');
    }

    /**
     * Delete a CTA Feature.
     */
    public function destroyCtaFeature($id): RedirectResponse
    {
        $feature = HowItWorksCtaFeature::findOrFail($id);
        $feature->delete();

        return redirect()->route('admin.how-it-works.index', ['tab' => 'cta'])->with('success', 'CTA Feature deleted successfully.');
    }

    /**
     * Store a new FAQ.
     */
    public function storeFaq(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        Faq::create($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'faqs'])->with('success', 'FAQ added successfully.');
    }

    /**
     * Update an existing FAQ.
     */
    public function updateFaq(Request $request, $id): RedirectResponse
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:Active,Inactive',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.how-it-works.index', ['tab' => 'faqs'])->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete an FAQ.
     */
    public function destroyFaq($id): RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.how-it-works.index', ['tab' => 'faqs'])->with('success', 'FAQ deleted successfully.');
    }
}
