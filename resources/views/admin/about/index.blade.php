@extends('layouts.admin')
@section('title', 'About Us CMS')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><div><h1 class="h3 fw-bold">About Us CMS</h1><p class="text-muted mb-0">Edit every section of your About Us page. Changes go live when you save.</p></div><a class="btn btn-outline-dark" href="{{ route('about') }}" target="_blank" rel="noopener">Preview About Us ↗</a></div>
@if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" role="alert"><strong>Please correct these fields:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form action="{{ route('admin.about.update') }}" method="post" enctype="multipart/form-data" id="about-editor">@csrf @method('PUT')
<p class="small text-muted">Images: JPG, PNG or WebP, up to 5 MB. Icon fields use Bootstrap icon names, such as shield-fill-check. Links accept /local-path, https:// or mailto:. Replace the default support, social and app-store links with your own destinations.</p>
@foreach($defaults as $section => $fields)
    @php($isList = in_array($section, \App\Support\AboutContent::lists(), true))
    <section class="card border-0 shadow-sm mb-4"><div class="card-header bg-white py-3"><h2 class="h5 mb-0">{{ ucwords(str_replace('_', ' ', $section)) }}</h2></div><div class="card-body">
    @if($isList)
        <input type="hidden" name="content[{{ $section }}]" value="__empty__">
        <div data-list="{{ $section }}">
        @foreach(old('content.'.$section, $content[$section]) === '__empty__' ? [] : old('content.'.$section, $content[$section]) as $index => $row)
            <div class="border rounded p-3 mb-3 repeat-row"><div class="row g-3">
            @foreach($fields[0] as $field => $default)<div class="col-md-{{ count($fields[0]) === 2 ? '6' : '4' }}"><label class="form-label w-100">{{ ucwords(str_replace('_', ' ', $field)) }}<input class="form-control mt-1" data-field="{{ $field }}" name="content[{{ $section }}][{{ $index }}][{{ $field }}]" value="{{ $row[$field] ?? '' }}" required maxlength="{{ $field === 'description' ? 6000 : 500 }}"></label></div>@endforeach
            </div><div class="d-flex gap-2 mt-2"><button type="button" class="btn btn-sm btn-outline-secondary" data-action="up" aria-label="Move row up">↑ Move up</button><button type="button" class="btn btn-sm btn-outline-secondary" data-action="down" aria-label="Move row down">↓ Move down</button><button type="button" class="btn btn-sm btn-outline-danger" data-action="remove">Remove</button></div></div>
        @endforeach
        </div>
        <template id="template-{{ $section }}"><div class="border rounded p-3 mb-3 repeat-row"><div class="row g-3">@foreach($fields[0] as $field => $default)<div class="col-md-{{ count($fields[0]) === 2 ? '6' : '4' }}"><label class="form-label w-100">{{ ucwords(str_replace('_', ' ', $field)) }}<input class="form-control mt-1" data-field="{{ $field }}" required maxlength="{{ $field === 'description' ? 6000 : 500 }}"></label></div>@endforeach</div><div class="d-flex gap-2 mt-2"><button type="button" class="btn btn-sm btn-outline-secondary" data-action="up">↑ Move up</button><button type="button" class="btn btn-sm btn-outline-secondary" data-action="down">↓ Move down</button><button type="button" class="btn btn-sm btn-outline-danger" data-action="remove">Remove</button></div></div></template>
        <button type="button" class="btn btn-outline-primary btn-sm" data-add="{{ $section }}">+ Add row</button>
    @else
        <div class="row g-3">@foreach($fields as $field => $default)
        @php($isImage = in_array($section.'.'.$field, \App\Support\AboutContent::imageFields(), true))
        <div class="{{ $field === 'description' || $isImage ? 'col-12' : 'col-md-6' }}"><label class="form-label" for="{{ $section }}-{{ $field }}">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
        @if(in_array($field, ['description', 'title', 'badge']))<textarea class="form-control" id="{{ $section }}-{{ $field }}" name="content[{{ $section }}][{{ $field }}]" rows="{{ $field === 'description' ? 4 : 2 }}" required maxlength="{{ $field === 'description' ? 6000 : 500 }}">{{ old('content.'.$section.'.'.$field, $content[$section][$field]) }}</textarea>
        @else<input class="form-control" id="{{ $section }}-{{ $field }}" name="content[{{ $section }}][{{ $field }}]" value="{{ old('content.'.$section.'.'.$field, $content[$section][$field]) }}" required maxlength="500">@endif
        @if($isImage)<div class="d-flex flex-wrap align-items-center gap-3 mt-2"><img src="{{ asset($content[$section][$field]) }}" alt="Current {{ $section }} image" style="width:150px;height:90px;object-fit:contain;background:#f3f4f6;border-radius:8px"><div><label class="form-label" for="upload-{{ $section }}-{{ $field }}">Upload replacement image</label><input id="upload-{{ $section }}-{{ $field }}" class="form-control" type="file" name="uploads[{{ $section }}_{{ $field }}]" accept="image/jpeg,image/png,image/webp"><small class="text-muted">Leave empty to keep the current image.</small></div></div>@endif
        </div>@endforeach</div>
    @endif
    </div></section>
@endforeach
<div class="bg-white border rounded p-3 position-sticky bottom-0 d-flex justify-content-between align-items-center" style="z-index:10"><span class="text-muted small" id="save-state" aria-live="polite">All sections are editable above.</span><button class="btn btn-warning fw-bold px-4" type="submit">Save About Us page</button></div>
</form>
<script>
(() => {
 const form = document.getElementById('about-editor');
 let dirty = false;
 const mark = () => { dirty = true; document.getElementById('save-state').textContent = 'You have unsaved changes.'; };
 const renumber = list => [...list.children].forEach((row, i) => row.querySelectorAll('[data-field]').forEach(input => input.name = `content[${list.dataset.list}][${i}][${input.dataset.field}]`));
 form.addEventListener('input', mark);
 form.addEventListener('change', mark);
 form.addEventListener('click', event => {
   const add = event.target.closest('[data-add]');
   if (add) { const list = form.querySelector(`[data-list="${add.dataset.add}"]`); if (list.children.length >= 30) return; list.append(document.getElementById(`template-${add.dataset.add}`).content.cloneNode(true)); renumber(list); list.lastElementChild.querySelector('input').focus(); mark(); return; }
   const button = event.target.closest('[data-action]'); if (!button) return;
   const row = button.closest('.repeat-row'), list = row.parentElement;
   if (button.dataset.action === 'remove') row.remove();
   if (button.dataset.action === 'up' && row.previousElementSibling) list.insertBefore(row, row.previousElementSibling);
   if (button.dataset.action === 'down' && row.nextElementSibling) list.insertBefore(row.nextElementSibling, row);
   renumber(list); mark();
 });
 form.addEventListener('submit', () => { dirty = false; form.querySelectorAll('[data-list]').forEach(renumber); });
 window.addEventListener('beforeunload', event => { if (dirty) { event.preventDefault(); event.returnValue = ''; } });
})();
</script>
@endsection
