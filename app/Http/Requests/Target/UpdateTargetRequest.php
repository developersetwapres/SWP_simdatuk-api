<?php

namespace App\Http\Requests\Target;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTargetRequest extends FormRequest
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
            'targets.*.period_month' => 'required|numeric|digits_between:1,12',
            'targets.*.period_year' => 'required|date_format:Y',
            'targets.*.appraisal_period' => 'required',
            'targets.*.year' => 'date_format:Y',
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
            'targets.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'targets.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'targets.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'targets.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'targets.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'targets.*.appraisal_period.required' => 'Periode penilaian tidak boleh kosong.',
            'targets.*.year.date_format' => 'Tahun Target harus dengan format YYYY.',
            'users.*.user_id' => 'required|numeric',
            'users.*.work_behavior_rating.required' => 'Rating perilaku kerja tidak boleh kosong.',
            'users.*.work_behavior_rating.numeric' => 'Rating perilaku kerja harus berupa angka.',
            'users.*.employee_performance_predicate.required' => 'Predikat kinerja pegawai tidak boleh kosong.',
            'users.*.employee_performance_predicate.numeric' => 'Predikat kinerja pegawai harus berupa angka.',
            'users.*.organizational_performance_achievement.required' => 'Capaian kinerja organisasi tidak boleh kosong.',
            'users.*.organizational_performance_achievement.numeric' => 'Capaian kinerja organisasi harus berupa angka.',
        ];
    }
}
