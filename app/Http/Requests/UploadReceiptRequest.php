<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTenant();
    }

    public function rules(): array
    {
        return [
            'receipt' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.mimes' => 'Receipt must be a JPG, PNG, or PDF file.',
            'receipt.max'   => 'Receipt file must not exceed 5MB.',
        ];
    }
}
