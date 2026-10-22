<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\HowItWorksCta;
use App\Models\HowItWorksCtaFeature;
use App\Models\HowItWorksPageSetting;
use App\Models\HowItWorksStep;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HowItWorksController extends Controller
{
    /**
     * Display the public How It Works page.
     */
    public function index(): View
    {
        $pageSetting = HowItWorksPageSetting::current();

        $steps = HowItWorksStep::query()
            ->where('status', 'Active')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $cta = HowItWorksCta::current();

        $ctaFeatures = HowItWorksCtaFeature::query()
            ->where('status', 'Active')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $faqs = Faq::query()
            ->where('status', 'Active')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('how-it-works', compact('pageSetting', 'steps', 'cta', 'ctaFeatures', 'faqs'));
    }
}
