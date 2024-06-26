<?php

namespace App\Http\Requests\GradeHistory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeHistoryEmployeeRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'grades.*.id' => 'numeric|nullable',
            'grades.*.grade_id' => 'required|numeric',
            'grades.*.effective_date' => 'required|date',
            'grades.*.decree_number' => 'max:160',
            'grades.*.status' => 'required|boolean',
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
            'grades.*.id.numeric' => 'ID harus berupa angka.',
            'grades.*.grade_id.required' => 'Golongan tidak boleh kosong.',
            'grades.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'grades.*.effective_date.required' => 'Tanggal efektif golongan tidak boleh kosong.',
            'grades.*.effective_date.date' => 'Tanggal efektif golongan harus berupa tanggal.',
            'grades.*.decree_number.max' => 'Nomor SK golongan tidak beloh lebih dari 160 karakter.',
            'grades.*.status.required' => 'Status tidak boleh kosong.',
            'grades.*.status.boolean' => 'Status harus berupa boolean.',
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
            'grades.*.id' => [
                'description' => 'Refers to the User ID of List Employee Recognition.',
                'example' => 1,
            ],
            'grades.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Grades.',
                'example' => 1,
            ],
            'grades.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Grades.',
                'example' => '2020-10-22',
            ],
            'grades.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Grades.',
                'example' => 'Nomor 50 Tahun 2008',
            ],
            'grades.*.status' => [
                'description' => 'Refers to the Status of Employee Grades.',
                'example' => 1,
            ],
        ];
    }
}
