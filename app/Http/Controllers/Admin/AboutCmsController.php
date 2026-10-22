<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\AboutContent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AboutCmsController extends Controller
{
    public function index()
    {
        return view('admin.about.index', ['content' => AboutContent::current(), 'defaults' => AboutContent::defaults()]);
    }
    public function update(Request $request)
    {
        foreach (AboutContent::lists() as $section) {
            if ($request->input("content.$section") === '__empty__') $request->merge(['content' => array_replace($request->input('content', []), [$section => []])]);
        }
        $rules = ['content' => ['required', 'array']];
        $safeLink = function ($attribute, $value, $fail) {
            if (! preg_match('~^(?:/(?!/)|https?://|mailto:)[^\s\\\\]*$~i', $value)) $fail('Use a local /path, an https:// URL, or a mailto: address.');
        };
        foreach (AboutContent::defaults() as $section => $fields) {
            $list = in_array($section, AboutContent::lists(), true);
            $rules["content.$section"] = [$list ? 'present' : 'required', 'array', $list ? 'max:30' : 'min:1'];
            foreach ($list ? $fields[0] : $fields as $field => $default) {
                $key = 'content.'.$section.($list ? '.*' : '').'.'.$field;
                $rules[$key] = ['required', 'string', 'max:'.($field === 'description' ? '6000' : '500')];
                if ($field === 'url' || str_ends_with($field, '_url')) $rules[$key][] = $safeLink;
                if ($field === 'icon') $rules[$key][] = 'regex:/^[a-z0-9-]+$/';
                if (in_array("$section.$field", AboutContent::imageFields(), true)) {
                    $rules[$key][] = 'regex:~^(?:images/|uploads/)[a-zA-Z0-9_./-]+\.(?:png|jpe?g|webp|gif)$~i';
                    $rules[$key][] = 'not_regex:~\.\.~';
                }
            }
        }
        foreach (AboutContent::imageFields() as $field) $rules['uploads.'.str_replace('.', '_', $field)] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
        $validated = $request->validate($rules);
        $content = [];
        foreach (AboutContent::defaults() as $section => $fields) {
            $list = in_array($section, AboutContent::lists(), true);
            $allowed = array_keys($list ? $fields[0] : $fields);
            $content[$section] = $list ? array_values(array_map(fn ($row) => Arr::only($row, $allowed), $validated['content'][$section])) : Arr::only($validated['content'][$section], $allowed);
        }
        $created = [];
        try {
            foreach (AboutContent::imageFields() as $field) {
                if ($file = $request->file('uploads.'.str_replace('.', '_', $field))) {
                    $path = 'uploads/about/'.$file->hashName();
                    $file->move(public_path('uploads/about'), basename($path));
                    $created[] = public_path($path);
                    Arr::set($content, $field, $path);
                }
            }
            Setting::set('about_page_content', json_encode($content, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), 'about');
        } catch (\Throwable $error) {
            foreach ($created as $path) @unlink($path);
            throw $error;
        }
        return redirect()->route('admin.about.index')->with('success', 'About Us page updated successfully. Your changes are now live.');
    }
}
