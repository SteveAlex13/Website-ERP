<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaySaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'cashier', 'sales'], true);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'debit_card', 'qris'])],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
