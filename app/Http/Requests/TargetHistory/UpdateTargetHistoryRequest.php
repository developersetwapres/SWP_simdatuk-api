<?php

namespace App\Http\Requests\TargetHistory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTargetHistoryRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'period_month' => 'required|numeric|digits_between:1,12',
            'period_year' => 'required|date_format:Y',
            'name' => 'required|max:160',
            'appraisal_period' => 'required|in:Q1,Q2,Q3,Q4,Tahunan',
            'year' => 'date_format:Y',
            'users.*.id' => 'numeric|nullable',
            'users.*.user_id' => 'required|numeric',
            'users.*.work_behavior_rating' => 'required|numeric',
            'users.*.employee_performance_predicate' => 'required|numeric',
            'users.*.organizational_performance_achievement' => 'required|numeric',
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
            'name.required' => 'Nama target tidak boleh kosong.',
            'name.max' => 'Nama target tidak boleh lebih dari 160 karakter.',
            'appraisal_period.required' => 'Periode penilaian tidak boleh kosong.',
            'appraisal_period.in' => 'Periode penilaian harus diantara Q1, Q2, Q3, Q4, Tahunan.',
            'year.date_format' => 'Tahun target harus dengan format YYYY.',
            'users.*.id.numeric' => 'ID harus berupa angka.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.work_behavior_rating.required' => 'Rating perilaku kerja tidak boleh kosong.',
            'users.*.work_behavior_rating.numeric' => 'Rating perilaku kerja harus berupa angka.',
            'users.*.employee_performance_predicate.required' => 'Predikat kinerja pegawai tidak boleh kosong.',
            'users.*.employee_performance_predicate.numeric' => 'Predikat kinerja pegawai harus berupa angka.',
            'users.*.organizational_performance_achievement.required' => 'Capaian kinerja organisasi tidak boleh kosong.',
            'users.*.organizational_performance_achievement.numeric' => 'Capaian kinerja organisasi harus berupa angka.',
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
                'description' => 'Refers to the Period Month of Employee Target.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Target.',
                'example' => '2020',
            ],
            'name' => [
                'description' => 'Refers to name of Employee Target.',
                'example' => 'PPK December 2020',
            ],
            'appraisal_period' => [
                'description' => 'Refers to the Appraisal Period of Employee Target. Q1, Q2, Q3, Q4, Tahunan',
                'example' => 'Q1',
            ],
            'year' => [
                'description' => 'Refers to the Year of Employee Target.',
                'example' => '2020',
            ],
            'users.*.id' => [
                'description' => 'Refers to the ID of List Employee Target.',
                'example' => 1,
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of List Employee Target.',
                'example' => 1,
            ],
            'users.*.work_behavior_rating' => [
                'description' => 'Refers to the Work Behavior Rating of Employee Target. 1=Diatas Ekspektasi, 2=Sesuai Ekspektasi, 3=Dibawah Ekspektasi',
                'example' => 1,
            ],
            'users.*.employee_performance_predicate' => [
                'description' => 'Refers to the Employee Performance Predicate of Employee Target. 1=Sangat Baik, 2=Baik, 3=Butuh Perbaikan, 4=Kurang, 5=Sangat Kurang',
                'example' => 1,
            ],
            'users.*.organizational_performance_achievement' => [
                'description' => 'Refers to the Orginizational Performance Achievement of Employee Target. 1=Sangat Baik, 2=Baik, 3=Cukup',
                'example' => 1,
            ],
        ];
    }
}
