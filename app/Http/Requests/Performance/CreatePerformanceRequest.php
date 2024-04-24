<?php

namespace App\Http\Requests\Performance;

class CreatePerformanceRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'performances.*.period_month' => 'required|numeric|digits_between:1,12',
            'performances.*.period_year' => 'required|date_format:Y',
            'performances.*.ppk_period' => 'required|max:160',
            'performances.*.work_performance_score' => 'required|numeric',
            'performances.*.description' => 'max:160',
        ];
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public static function messages(): array
    {
        return [
            'performances.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'performances.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'performances.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'performances.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'performances.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'performances.*.ppk_period.required' => 'PPK periode tidak boleh kosong.',
            'performances.*.ppk_period.max' => 'PPK periode tidak boleh lebih dari 160 karakter.',
            'performances.*.work_performance_score.required' => 'Nilai prestasi kerja tidak boleh kosong.',
            'performances.*.work_performance_score.numeric' => 'Nilai prestasi kerja harus berupa angka.',
            'performances.*.description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
        ];
    }

    /**
     * Description for scribe
     *
     * @return array
     */
    public static function bodyParameters(): array
    {
        return [
            'performances.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Performance.',
                'example' => 3,
            ],
            'performances.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Performance.',
                'example' => '2020',
            ],
            'performances.*.ppk_period' => [
                'description' => 'Refers to the PPK Period of Employee Performance.',
                'example' => '20',
            ],
            'performances.*.work_performance_score' => [
                'description' => 'Refers to the Work Performance Score of Employee Performance.',
                'example' => 5,
            ],
            'performances.*.description' => [
                'description' => 'Refers to the Description of Employee Performance.',
                'example' => 'Prestasi kinerja',
            ],
        ];
    }
}
