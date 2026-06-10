<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTenant();
    }

    public function rules(): array
    {
        return [
            'room_id'      => ['required', 'exists:rooms,id'],
            'move_in_date' => ['required', 'date', 'after_or_equal:today'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'move_in_date.after_or_equal' => 'Move-in date must be today or a future date.',
            'room_id.exists'              => 'The selected room does not exist.',
        ];
    }
}
