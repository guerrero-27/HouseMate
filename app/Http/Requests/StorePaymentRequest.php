<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id'        => ['required', 'exists:users,id'],
            'reservation_id' => ['required', 'exists:reservations,id'],
            'billing_month'  => ['required', 'date_format:Y-m'],
            'amount'         => ['required', 'numeric', 'min:1'],
            'payment_type'   => ['required', 'in:rent,electricity,water,other'],
            'due_date'       => ['required', 'date'],
        ];
    }
}
