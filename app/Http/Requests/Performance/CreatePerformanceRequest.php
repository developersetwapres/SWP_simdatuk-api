<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;
class CreatePerformanceRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'period_month' => 'required|numeric|digits_between:1,12',
            'period_year' => 'required|date_format:Y',
            'performance_period' => 'required|max:160',
            'name' => 'required|max:160',
            'users.*.user_id' => 'required|numeric',
            'users.*.work_performance_score' => 'required|numeric',
            'description' => 'max:160',
        ];
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'performance_period.required' => 'PPK periode tidak boleh kosong.',
            'performance_period.max' => 'PPK periode tidak boleh lebih dari 160 karakter.',
            'name.required' => 'Nama nilai prestasi kerja tidak boleh kosong.',
            'name.max' => 'Nama nilai prestasi tidak boleh lebih dari 160 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.work_performance_score.required' => 'Nilai prestasi kerja tidak boleh kosong.',
            'users.*.work_performance_score.numeric' => 'Nilai prestasi kerja harus berupa angka.',
            'description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
        ];
    }

    /**
     * Description for scribe
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'period_month' => [
                'description' => 'Refers to the Period Month of Employee Performance.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Performance.',
                'example' => '2020',
            ],
            'performance_period' => [
                'description' => 'Refers to the PPK Period of Employee Performance.',
                'example' => 'PPK Desember 2020',
            ],
            'name' => [
              'description' => 'Refers to name of Work Performance Score',
              'example'  => 'PPK December 2020',
            ],
            'users.*.work_performance_score' => [
                'description' => 'Refers to the Work Performance Score of Employee Performance.',
                'example' => 80,
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the ID of employee',
                'example' => 1,
            ],
            'description' => [
                'description' => 'Refers to the Description of Employee Performance.',
                'example' => 'Penilaian Bulanan',
            ],
        ];
    }
}
