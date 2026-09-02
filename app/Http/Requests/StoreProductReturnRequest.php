<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductReturnRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $reference = $this->string('reference')->toString();
        $type = Str::before($reference, ':');

        $this->merge([
            'type' => $type,
            'reference_type' => $type === 'sale' ? 'App\\Models\\Sale' : 'App\\Models\\Purchase',
            'reference_id' => (int) Str::after($reference, ':'),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'finance', 'warehouse'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string'],
            'number' => ['required', 'string', 'max:50', 'unique:product_returns,number'],
            'type' => ['required', 'in:sale,purchase'],
            'reference_type' => ['required', 'in:App\\Models\\Sale,App\\Models\\Purchase'],
            'reference_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
