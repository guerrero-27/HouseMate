<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        // When updating, ignore the current room's room_number in the unique check
        $roomId = $this->route('room')->id;

        return [
            'room_number'  => ['required', 'string', 'max:50', "unique:rooms,room_number,{$roomId}"],
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
