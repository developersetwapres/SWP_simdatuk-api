<?php

namespace App\Http\Requests\SKP;

use Illuminate\Foundation\Http\FormRequest;

class CreateSKPRequest extends FormRequest
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
            'users.*.user_id' => 'required|numeric',
            'rating_work_behavior'=>'required',
            'employee_performance_predicate'=>'required',
            'organization_performance_achievement'=>'required',
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
            'name.required' => 'Nama diklat tidak boleh kosong.',
            'name.max' => 'Nama diklat tidak boleh lebih dari 160 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'rating_work_behavior.required'=>'Rating perilaku kerja tidak boleh kosong',
            'employee_performance_predicate.required'=>'Predikat kinerja pekerja tidak boleh kosong',
            'organization_performance_achievement.required'=>'Capaian kinerja organisasi tidak boleh kosong',
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
                'description' => 'Refers to the Period Month of Employee SKP.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee SKP.',
                'example' => '2020',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee SKP.',
                'example' => 'Sepadya tahun 1994',
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of List Employee Training.',
                'example' => public_path('/img/logo.svg'),
            ],
            'rating_work_behavior' => [
                'description' => 'Refers to rating work behavior of the Employee',
                'example' => 'Sesuai ekspektasi',
            ],
            'employee_performance_predicate' => [
                'description' => 'Refers to performance predicate of the Employee',
                'example' => 'Sangat Baik',
            ],
            'organization_performance_achievement' => [
                'description' => 'Refers to performance achievement of Employee organization',
                'example' => 'Baik',
            ],
        ];
    }
}
