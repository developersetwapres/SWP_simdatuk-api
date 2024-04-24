<?php

namespace App\Http\Requests\Target;

class CreateTargetRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'targets.*.period_month' => 'numeric|digits_between:1,12',
            'targets.*.period_year' => 'date_format:Y',
            'targets.*.appraisal_period' => 'numeric',
            'targets.*.year' => 'date_format:Y',
            'targets.*.work_behavior_rating' => 'numeric',
            'targets.*.employee_performance_predicate' => 'numeric',
            'targets.*.organizational_performance_achievement' => 'numeric',
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
            'targets.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'targets.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'targets.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'targets.*.appraisal_period.numeric' => 'Periode penilaian harus berupa angka.',
            'targets.*.year.date_format' => 'Tahun SKP harus dengan format YYYY.',
            'targets.*.work_behavior_rating.numeric' => 'Rating perilaku kerja harus berupa angka.',
            'targets.*.employee_performance_predicate.numeric' => 'Predikat kinerja pegawai harus berupa angka.',
            'targets.*.organizational_performance_achievement.numeric' => 'Capaian kinerja organisasi harus berupa angka.',
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
            'targets.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Target.',
                'example' => 3,
            ],
            'targets.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Target.',
                'example' => '2020',
            ],
            'targets.*.appraisal_period' => [
                'description' => 'Refers to the Appraisal Period of Employee Target.',
                'example' => 1,
            ],
            'targets.*.year' => [
                'description' => 'Refers to the Year of Employee Target.',
                'example' => '2020',
            ],
            'targets.*.work_behavior_rating' => [
                'description' => 'Refers to the Work Behavior Rating of Employee Target.',
                'example' => 5,
            ],
            'targets.*.employee_performance_predicate' => [
                'description' => 'Refers to the Employee Performance Predicate of Employee Target.',
                'example' => 5,
            ],
            'targets.*.organizational_performance_achievement' => [
                'description' => 'Refers to the Orginizational Performance Achievement of Employee Target.',
                'example' => 5,
            ],
        ];
    }
}
