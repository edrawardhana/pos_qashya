<?php

namespace App\Http\Requests;

use App\Models\StockLog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StockAdjustRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(StockLog::TYPES)],
            'qty' => ['required', 'integer', 'min:0', 'max:99999'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $type = $this->input('type');
            $qty = (int) $this->input('qty');

            if (in_array($type, [StockLog::TYPE_MASUK, StockLog::TYPE_KELUAR], true) && $qty < 1) {
                $v->errors()->add('qty', 'Jumlah harus minimal 1 untuk stok masuk atau keluar.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'type.required' => 'Jenis perubahan wajib dipilih.',
            'type.in' => 'Jenis perubahan tidak valid.',
            'qty.required' => 'Jumlah wajib diisi.',
            'qty.integer' => 'Jumlah harus berupa bilangan bulat.',
            'qty.min' => 'Jumlah tidak boleh negatif.',
            'qty.max' => 'Jumlah terlalu besar.',
            'note.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }
}