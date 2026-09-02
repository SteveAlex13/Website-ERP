<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'superadmin';
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::in(['superadmin', 'admin', 'cashier', 'sales', 'purchasing', 'warehouse', 'finance'])],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $targetUser = $this->route('user');
                $removesSuperadmin = $targetUser instanceof User
                    && $targetUser->role === 'superadmin'
                    && $this->input('role') !== 'superadmin';

                if ($removesSuperadmin && User::query()->where('role', 'superadmin')->count() <= 1) {
                    $validator->errors()->add('role', 'Superadmin terakhir tidak dapat diturunkan rolenya.');
                }
            },
        ];
    }
}
