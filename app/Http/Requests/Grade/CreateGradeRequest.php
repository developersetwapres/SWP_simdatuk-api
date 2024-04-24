<?php

namespace App\Http\Requests\Grade;

class CreateGradeRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'grades.*.period_month' => 'numeric|digits_between:1,12',
            'grades.*.period_year' => 'date_format:Y',
            'grades.*.grade_id' => 'numeric',
            'grades.*.effective_date' => 'date',
            'grades.*.decree_name' => 'max:160',
            'grades.*.decree_document' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
            'grades.*.type_of_decree' => 'numeric',
            'grades.*.decree_number' => 'max:160',
            'grades.*.decree_date' => 'date',
            'grades.*.description' => 'max:160',
            'grades.*.status' => 'numeric',
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
            'grades.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'grades.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'grades.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'grades.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'grades.*.effective_date.date' => 'Tanggal efektif golongan harus berupa tanggal.',
            'grades.*.decree_name.max' => 'SK golongan tidak boleh lebih dari 160 karakter.',
            'grades.*.decree_document.file' => 'SK golongan harus berupa file.',
            'grades.*.decree_document.extensions' => 'SK golongan harus berupa jpg, jpeg atau png.',
            'grades.*.decree_document.max' => 'SK golongan tidak boleh lebih dari 2MB',
            'grades.*.type_of_decree.numeric' => 'Jenis SK golongan harus berupa angka.',
            'grades.*.decree_number.max' => 'Nomor SK golongan tidak beloh lebih dari 160 karakter.',
            'grades.*.decree_date.date' => 'Tanggal SK golongan harus berupa tanggal.',
            'grades.*.description.max' => 'Keterangan golongan tidak boleh lebih dari 160 karakter.',
            'grades.*.status.numeric' => 'Status golongan harus berupa angka.',
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
            'grades.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Grade.',
                'example' => 3,
            ],
            'grades.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Grade.',
                'example' => '2010',
            ],
            'grades.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Grade.',
                'example' => 1,
            ],
            'grades.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Grade.',
                'example' => '2020-10-22',
            ],
            'grades.*.decree_name' => [
                'description' => 'Refers to the Decree Name of Employee Grade.',
                'example' => '58/Set.Neg/Pers.In/6/1993, 12-06-1993',
            ],
            'grades.*.decree_document' => [
                'description' => 'Refers to the Decree Document of Employee Grade.',
                'example' => public_path('/img/logo.svg'),
            ],
            'grades.*.type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Grade.',
                'example' => 1,
            ],
            'grades.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Grade.',
                'example' => '58/Set.Neg/Pers.In/6/1993, 12-06-1993',
            ],
            'grades.*.decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Grade.',
                'example' => '2020-10-22',
            ],
            'grades.*.description' => [
                'description' => 'Refers to the Description of Employee Grade.',
                'example' => 'Kenaikan Pangkat Reguler',
            ],
            'grades.*.status' => [
                'description' => 'Refers to the Status of Employee Grade.',
                'example' => 1,
            ],
        ];
    }
}
