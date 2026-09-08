<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaySaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'cashier', 'sales', 'finance'], true);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'paid_amount' => ['nullable', 'numeric', 'min:1'],
            'redirect_to_invoices' => ['nullable', 'boolean'],
            'redirect_to_orders' => ['nullable', 'boolean'],
        ];
    }
}
