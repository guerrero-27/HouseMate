<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin can create rooms
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'room_number'  => ['required', 'string', 'max:50', 'unique:rooms,room_number'],
            'room_type'    => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'monthly_rate' => ['required', 'numeric', 'min:1'],
            'capacity'     => ['required', 'integer', 'min:1'],
            'floor_number' => ['nullable', 'integer', 'min:1'],
            'status'       => ['required', 'in:available,occupied,maintenance'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'images'       => ['nullable', 'array', 'max:5'],
            'images.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
