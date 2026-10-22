<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parking_spot_id' => ['required', 'integer', 'exists:parking_spots,id'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'start_time' => ['required', 'date', 'after_or_equal:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ];
    }

    /**
     * Return custom JSON response on validation failure for API.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
                'timestamp' => now()->toIso8601String(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
