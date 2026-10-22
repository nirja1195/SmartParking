<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'parking_location_id' => ['required', 'exists:parking_locations,id'],
            'zone_id' => ['required', 'exists:parking_zones,id'],
            'slot_id' => ['required', 'exists:parking_slots,id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'entry_time' => ['required', 'date'],
            'expected_exit_time' => ['required', 'date', 'after:entry_time'],
        ];
    }
}
