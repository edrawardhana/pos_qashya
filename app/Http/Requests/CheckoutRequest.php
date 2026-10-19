<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('kasir') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_name' => trim((string) $this->input('customer_name')) ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'method' => ['required', Rule::in([Transaction::METHOD_CASH, Transaction::METHOD_TRANSFER])],
            'paid' => ['nullable', 'integer', 'min:0', 'required_if:method,'.Transaction::METHOD_CASH],
            'customer_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
            'items.*.qty.min' => 'Jumlah minimal 1.',
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'method.in' => 'Metode pembayaran tidak valid.',
            'paid.required_if' => 'Jumlah bayar wajib diisi untuk pembayaran tunai.',
            'paid.integer' => 'Jumlah bayar harus berupa angka.',
            'paid.min' => 'Jumlah bayar tidak boleh negatif.',
        ];
    }
}