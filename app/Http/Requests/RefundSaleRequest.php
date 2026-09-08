<?php

namespace App\Http\Requests;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RefundSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'cashier', 'finance'], true);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
            'quotation_key' => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isValid = User::query()
                        ->where('role', 'superadmin')
                        ->whereNotNull('quotation_delete_key')
                        ->get(['quotation_delete_key'])
                        ->contains(fn (User $user): bool => Hash::check((string) $value, $user->quotation_delete_key));

                    if (! $isValid) {
                        $fail('Key otorisasi refund tidak valid.');
                    }
                },
            ],
        ];
    }
}
