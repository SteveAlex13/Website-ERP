<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RefundSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'cashier', 'finance'], true);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['reason' => ['required', 'string', 'max:255']];
    }
}
