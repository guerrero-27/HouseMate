<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'action'      => ['required', 'in:verify,return'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
