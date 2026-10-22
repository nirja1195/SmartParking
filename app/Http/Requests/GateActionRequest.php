<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GateActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'], // Booking ID or Vehicle Number
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
}
