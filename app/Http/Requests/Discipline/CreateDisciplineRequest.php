<?php

namespace App\Http\Requests\Discipline;

class CreateDisciplineRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'disciplinaries.*.period_month' => 'required|numeric|digits_between:1,12',
            'disciplinaries.*.period_year' => 'required|date_format:Y',
            'disciplinaries.*.grade_id' => 'required|numeric',
            'disciplinaries.*.position' => 'required|numeric',
            'disciplinaries.*.penalty' => 'required|numeric',
            'disciplinaries.*.decree_number' => 'max:160',
            'disciplinaries.*.date_of_decree' => 'date',
            'disciplinaries.*.start_date' => 'required|date',
            'disciplinaries.*.end_date' => 'required|date',
            'disciplinaries.*.status' => 'required|numeric',
            'disciplinaries.*.description' => 'max:160',
            'disciplinaries.*.authorizing_officer' => 'required|numeric',
            'disciplinaries.*.name_of_authorizing_officer' => 'max:160',
            'disciplinaries.*.level' => 'required|numeric',
            'disciplinaries.*.type' => 'required|numeric',
            'disciplinaries.*.validity_period' => 'numeric',
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
            'disciplinaries.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'disciplinaries.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'disciplinaries.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'disciplinaries.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'disciplinaries.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'disciplinaries.*.grade_id.required' => 'Golongan tidak boleh kosong.',
            'disciplinaries.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'disciplinaries.*.position.required' => 'Jabatan tidak boleh kosong.',
            'disciplinaries.*.position.numeric' => 'Jabatan harus berupa angka.',
            'disciplinaries.*.penalty.required' => 'Hukuman disiplin tidak boleh kosong.',
            'disciplinaries.*.penalty.numeric' => 'Hukuman disiplin berupa angka.',
            'disciplinaries.*.decree_number.max' => 'No SK hukuman disiplin tidak boleh lebih dari 160 karakter.',
            'disciplinaries.*.date_of_decree.date' => 'Tanggal SK hukuman disiplin harus berupa tanggal.',
            'disciplinaries.*.start_date.required' => 'Mulai tanggal hukuman tidak boleh kosong.',
            'disciplinaries.*.start_date.date' => 'Mulai tanggal hukuman harus berupa tanggal.',
            'disciplinaries.*.end_date.required' => 'Akhir tanggal hukuman tidak boleh kosong.',
            'disciplinaries.*.end_date.date' => 'Akhir tanggal hukuman harus berupa tanggal.',
            'disciplinaries.*.status.required' => 'Status tidak boleh kosong.',
            'disciplinaries.*.status.numeric' => 'Status harus berupa angka.',
            'disciplinaries.*.description.max' => 'Uraian tidak boleh lebih dari 160 karakter.',
            'disciplinaries.*.authorizing_officer.numeric' => 'Pejabat berwenang harus berupa angka.',
            'disciplinaries.*.name_of_authorizing_officer.max' => 'Nama pejabat berwenang tidak boleh lebih dari 160 karakter.',
            'disciplinaries.*.level.required' => 'Tingkat hukuman tidak boleh kosong.',
            'disciplinaries.*.level.numeric' => 'Tingkat hukuman harus berupa angka.',
            'disciplinaries.*.type.required' => 'Jenis hukuman tidak boleh kosong.',
            'disciplinaries.*.type.numeric' => 'Jenis hukuman harus berupa angka.',
            'disciplinaries.*.validity_period.numeric' => 'Masa berlaku harus berupa angka.',
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
            'disciplinaries.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Discipline.',
                'example' => 3,
            ],
            'disciplinaries.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Discipline.',
                'example' => '2020',
            ],
            'disciplinaries.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.position' => [
                'description' => 'Refers to the Position of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.penalty' => [
                'description' => 'Refers to the Penalty of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Discipline.',
                'example' => 'Nomor 112 Tahun 2013',
            ],
            'disciplinaries.*.date_of_decree' => [
                'description' => 'Refers to the Date of Decree of Employee Discipline.',
                'example' => '2020-10-22',
            ],
            'disciplinaries.*.start_date' => [
                'description' => 'Refers to the Start Date of Employee Discipline.',
                'example' => '2020-10-22',
            ],
            'disciplinaries.*.end_date' => [
                'description' => 'Refers to the End Date of Employee Discipline.',
                'example' => '2020-10-22',
            ],
            'disciplinaries.*.status' => [
                'description' => 'Refers to the Status of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.description' => [
                'description' => 'Refers to the Description of Employee Discipline.',
                'example' => 'Melanggar aturan',
            ],
            'disciplinaries.*.authorizing_officer' => [
                'description' => 'Refers to the Authorizing Officer of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.name_of_authorizing_officer' => [
                'description' => 'Refers to the Name of Authorizing Officer of Employee Discipline.',
                'example' => 'Sapto Harjono Wahjoe Sedjati',
            ],
            'disciplinaries.*.level' => [
                'description' => 'Refers to the Level of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.type' => [
                'description' => 'Refers to the Type of Employee Discipline.',
                'example' => 1,
            ],
            'disciplinaries.*.validity_period' => [
                'description' => 'Refers to the Validity Period of Employee Discipline.',
                'example' => 10,
            ],
        ];
    }
}
