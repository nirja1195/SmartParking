@extends('layouts.admin')

@section('title', 'Edit Parking Facility')
@section('page-title', 'Edit Facility Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Edit {{ $lot->name }}</h5>

            <form method="POST" action="{{ route('admin.parking-lots.update', $lot->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label fw-semibold">Facility Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $lot->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="code" class="form-label fw-semibold">Facility Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $lot->code) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $lot->address) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="city" class="form-label fw-semibold">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $lot->city) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="hourly_rate" class="form-label fw-semibold">Hourly Rate ($)</label>
                        <input type="number" step="0.01" class="form-control" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $lot->hourly_rate) }}" required>
                    </div>

                    <div class="col-md-6 d-flex align-items-center mt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $lot->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Facility Active / Operational</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.parking-lots.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Facility</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
