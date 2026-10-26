<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if ($v->errors()->any()) {
                return;
            }

            $from = $this->from();
            $to = $this->to();

            if ($to->lt($from)) {
                $v->errors()->add('to', 'Tanggal akhir tidak boleh sebelum tanggal awal.');
            } elseif ($from->diffInDays($to) > 366) {
                $v->errors()->add('to', 'Rentang laporan maksimal 1 tahun.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'from.date_format' => 'Format tanggal awal tidak valid.',
            'to.date_format' => 'Format tanggal akhir tidak valid.',
        ];
    }

    /** Awal hari pada tanggal "dari", default awal bulan ini. */
    public function from(): Carbon
    {
        return $this->filled('from')
            ? Carbon::createFromFormat('Y-m-d', $this->input('from'))->startOfDay()
            : now()->startOfMonth();
    }

    /** Akhir hari pada tanggal "sampai", default hari ini. */
    public function to(): Carbon
    {
        return $this->filled('to')
            ? Carbon::createFromFormat('Y-m-d', $this->input('to'))->endOfDay()
            : now()->endOfDay();
    }
}