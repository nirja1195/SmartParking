@extends('layouts.admin')
@section('title', 'Admin Login Page CMS')
@section('content')
<div class="d-flex flex-wrap justify-content-between gap-3 mb-4"><div><h1 class="h3 fw-bold">Admin Login Page CMS</h1><p class="text-muted">Update the login page text, images and branding. Changes are live after saving.</p></div><a href="{{ route('admin.login.preview') }}" target="_blank" rel="noopener" class="btn btn-outline-dark align-self-start">Preview login page ↗</a></div>
@if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="alert alert-info">Manage your email and password under <a href="{{ route('admin.profile.index') }}">Admin Profile</a>. OTP uses the application's configured email service.</div>
<form method="post" action="{{ route('admin.login-page.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
@foreach($defaults as $section => $fields)<section class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h2 class="h5 mb-0 py-2">{{ ucwords(str_replace('_', ' ', $section)) }}</h2></div><div class="card-body"><div class="row g-3">
@foreach($fields as $field => $default)<div class="col-md-6"><label class="form-label" for="{{ $section }}-{{ $field }}">{{ ucwords(str_replace('_', ' ', $field)) }}</label><textarea class="form-control" id="{{ $section }}-{{ $field }}" name="content[{{ $section }}][{{ $field }}]" rows="{{ str_contains($default, "\n") ? 3 : 2 }}" maxlength="1000" required>{{ old('content.'.$section.'.'.$field, $content[$section][$field]) }}</textarea>
@if(in_array($field, ['image', 'logo']))<img src="{{ asset($content[$section][$field]) }}" alt="Current {{ $field }}" style="height:120px;max-width:100%;object-fit:contain" class="my-3"><label class="form-label d-block" for="upload-{{ $field }}">Upload replacement {{ $field }}</label><input class="form-control" type="file" name="{{ $field === 'image' ? 'background' : 'logo' }}" id="upload-{{ $field }}" accept="image/png,image/jpeg,image/webp"><small class="text-muted">PNG, JPG or WebP. {{ $field === 'image' ? '8' : '2' }} MB maximum. Leave empty to keep current image.</small>@endif
</div>@endforeach</div></div></section>@endforeach
<div class="position-sticky bottom-0 bg-white border rounded p-3 text-end" style="z-index:5"><button type="submit" class="btn btn-warning fw-bold">Save login page</button></div>
</form>
@endsection
