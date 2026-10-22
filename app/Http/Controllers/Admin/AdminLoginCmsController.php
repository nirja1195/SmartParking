<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\AdminLoginContent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AdminLoginCmsController extends Controller
{
    public function index()
    {
        return view('admin.login-page.index', ['content' => AdminLoginContent::current(), 'defaults' => AdminLoginContent::defaults()]);
    }

    public function update(Request $request)
    {
        $rules = ['content' => ['required', 'array'], 'background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'], 'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']];
        foreach (AdminLoginContent::defaults() as $section => $fields) {
            foreach ($fields as $field => $default) {
                $key = "content.$section.$field";
                $rules[$key] = ['required', 'string', 'max:1000'];
                if ($field === 'icon') $rules[$key][] = 'regex:/^[a-z0-9-]+$/';
                if ($field === 'contact_url') $rules[$key][] = 'regex:~^(?:/(?!/)|https?://|mailto:)[^\s\\\\]*$~i';
                if (in_array($field, ['image', 'logo'], true)) {
                    $rules[$key][] = 'regex:~^(?:images/|uploads/)[a-zA-Z0-9_./-]+\.(?:png|jpe?g|webp|svg)$~i';
                    $rules[$key][] = 'not_regex:~\.\.~';
                }
            }
        }
        $validated = $request->validate($rules);
        $content = [];
        foreach (AdminLoginContent::defaults() as $section => $fields) $content[$section] = Arr::only($validated['content'][$section], array_keys($fields));
        $created = [];
        try {
            foreach (['background' => 'hero.image', 'logo' => 'branding.logo'] as $input => $key) {
                if ($file = $request->file($input)) {
                    $path = 'uploads/admin-login/'.$file->hashName();
                    $file->move(public_path('uploads/admin-login'), basename($path));
                    $created[] = public_path($path);
                    Arr::set($content, $key, $path);
                }
            }
            Setting::set('admin_login_content', json_encode($content, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), 'admin_login');
        } catch (\Throwable $error) {
            foreach ($created as $path) @unlink($path);
            throw $error;
        }
        return back()->with('success', 'Admin login page updated successfully.');
    }
}
