<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('document_type') === 'pos' && $this->input('submit_action', 'transaction') === 'transaction' && ! $this->filled('payment_method')) {
            $this->merge(['payment_method' => 'cash']);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'sales', 'cashier'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPointOfSaleTransaction = $this->input('document_type') === 'pos' && $this->input('submit_action', 'transaction') === 'transaction';

        return [
            'number' => ['required', 'string', 'max:50', 'unique:sales,number'],
            'document_type' => ['required', 'in:quotation,order,pos'],
            'submit_action' => ['nullable', Rule::in(['transaction', 'bill'])],
            'bill_name' => [Rule::requiredIf($this->input('submit_action') === 'bill'), 'nullable', 'string', 'max:100'],
            'customer_id' => [Rule::requiredIf($this->input('document_type') !== 'pos'), 'nullable', 'exists:contacts,id'],
            'sales_user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'discount_type' => ['nullable', 'in:fixed,percentage'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => [Rule::requiredIf($isPointOfSaleTransaction), 'nullable', Rule::in(['cash', 'bank_transfer'])],
            'bank_account_id' => [Rule::requiredIf($isPointOfSaleTransaction), 'nullable', 'exists:bank_accounts,id'],
            'paid_amount' => [Rule::requiredIf($isPointOfSaleTransaction && $this->input('payment_method') === 'cash'), 'nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
