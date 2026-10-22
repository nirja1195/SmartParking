<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiInsightsService;
use Illuminate\View\View;

class AiInsightsController extends Controller
{
    protected AiInsightsService $aiService;

    public function __construct(AiInsightsService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Requirement #42: AI Insights (/admin/ai-insights).
     */
    public function index(): View
    {
        $insights = $this->aiService->getInsights();

        return view('admin.ai-insights.index', compact('insights'));
    }
}
