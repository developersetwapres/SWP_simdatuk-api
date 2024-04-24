<?php

namespace App\Http\Requests\Salary;

class CreateSalaryRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'salaries.*.period_month' => 'required|numeric|digits_between:1,12',
            'salaries.*.period_year' => 'required|date_format:Y',
            'salaries.*.grade_id' => 'required|numeric',
            'salaries.*.effective_date' => 'date',
            'salaries.*.decree_number' => 'max:160',
            'salaries.*.length_of_service_month' => 'required|numeric|digits_between:1,12',
            'salaries.*.length_of_service_year' => 'required|date_format:Y',
            'salaries.*.previous_basic_salary' => 'required|numeric',
            'salaries.*.new_basic_salary' => 'required|numeric',
            'salaries.*.description' => 'max:160',
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
            'salaries.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'salaries.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'salaries.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'salaries.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'salaries.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'salaries.*.grade_id.required' => 'Golongan tidak boleh kosong.',
            'salaries.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'salaries.*.effective_date.date' => 'Tanggal efektif golongan harus berupa tanggal.',
            'salaries.*.decree_number.max' => 'No SK tidak boleh lebih 160 karakter.',
            'salaries.*.length_of_service_month.required' => 'Bulan masa kerja golongan tidak boleh kosong.',
            'salaries.*.length_of_service_month.numeric' => 'Bulan masa kerja golongan harus berupa angka.',
            'salaries.*.length_of_service_month.digits_between' => 'Bulan masa kerja golongan harus diantara 1 hingga 12.',
            'salaries.*.length_of_service_year.required' => 'Tahun masa kerja golongan tidak boleh kosong.',
            'salaries.*.length_of_service_year.date_format' => 'Tahun masa kerja golongan harus berupa YYYY.',
            'salaries.*.previous_basic_salary.required' => 'Gaji pokok lama tidak boleh kosong.',
            'salaries.*.previous_basic_salary.numeric' => 'Gaji pokok lama harus berupa angka.',
            'salaries.*.new_basic_salary.required' => 'Gaji pokok baru tidak boleh kosong.',
            'salaries.*.new_basic_salary.numeric' => 'Gaji pokok baru harus berupa angka.',
            'salaries.*.description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
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
            'salaries.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Salary.',
                'example' => 3,
            ],
            'salaries.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Salary.',
                'example' => '2020',
            ],
            'salaries.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Salary.',
                'example' => 1,
            ],
            'salaries.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Salary.',
                'example' => '2020-10-22',
            ],
            'salaries.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Salary.',
                'example' => '58/Set.Neg/Pers.In/6/1993, 12-06-1993',
            ],
            'salaries.*.length_of_service_month' => [
                'description' => 'Refers to the Length of Service Month of Employee Salary.',
                'example' => 10,
            ],
            'salaries.*.length_of_service_year' => [
                'description' => 'Refers to the Length of Service Year of Employee Salary.',
                'example' => '2020',
            ],
            'salaries.*.previous_basic_salary' => [
                'description' => 'Refers to the Previous Basic Salary of Employee Salary.',
                'example' => 4000000,
            ],
            'salaries.*.new_basic_salary' => [
                'description' => 'Refers to the New Basic Salary of Employee Salary.',
                'example' => 5000000,
            ],
            'salaries.*.description' => [
                'description' => 'Refers to the Description of Employee Salary.',
                'example' => 'Kenaikan Gaji',
            ],
        ];
    }
}
