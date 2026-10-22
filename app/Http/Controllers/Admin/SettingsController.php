<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Requirement #45: Admin Settings (/admin/settings).
     */
    public function index(): View
    {
        $settingsGrouped = Setting::getAllGrouped();

        return view('admin.settings.index', compact('settingsGrouped'));
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            if ($key === 'group') {
                continue;
            }
            $group = $request->input('group', 'general');
            Setting::set($key, is_null($value) ? '' : (string) $value, $group);
        }

        return back()->with('success', 'System configurations successfully updated.');
    }
}
