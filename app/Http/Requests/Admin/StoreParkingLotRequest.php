<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreParkingLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:parking_lots,code'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'total_spots' => ['required', 'integer', 'min:1', 'max:2000'],
            'hourly_rate' => ['required', 'numeric', 'min:0.01', 'max:1000.00'],
            'is_active' => ['sometimes', 'boolean'],
            'floors' => ['nullable', 'array'],
            'floors.*' => ['string', 'max:50'],
        ];
    }
}
