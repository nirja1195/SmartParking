<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'license_plate' => ['required', 'string', 'max:25', 'unique:vehicles,license_plate'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
            'vehicle_type' => ['required', 'in:compact,standard,ev,truck'],
        ];
    }
}
