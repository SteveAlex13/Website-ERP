<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpensePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['superadmin', 'admin', 'purchasing', 'finance'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:50', Rule::unique('expense_purchases', 'number')->ignore($this->route('expense_purchase'))],
            'supplier_id' => ['nullable', 'exists:contacts,id'],
            'date' => ['required', 'date'],
            'purchase_type' => ['required', Rule::in(['barang', 'kebutuhan', 'asset'])],
            'document_type' => ['required', Rule::in(['quotation_po', 'delivery_order', 'invoice', 'retur', 'piutang'])],
            'description' => ['required', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'gt:0'],
            'paid_amount' => ['required', 'numeric', 'min:0', 'lte:total'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
