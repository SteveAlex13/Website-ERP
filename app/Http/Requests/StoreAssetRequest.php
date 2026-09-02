<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'finance'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:assets,code'],
            'name' => ['required', 'string', 'max:255'],
            'acquired_at' => ['required', 'date'],
            'acquisition_cost' => ['required', 'numeric', 'gt:0'],
            'residual_value' => ['required', 'numeric', 'min:0', 'lte:acquisition_cost'],
            'useful_life_months' => ['required', 'integer', 'min:1'],
        ];
    }
}
