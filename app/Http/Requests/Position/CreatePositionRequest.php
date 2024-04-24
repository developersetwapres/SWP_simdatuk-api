<?php

namespace App\Http\Requests\Position;

class CreatePositionRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'positions.*.period_month' => 'required|numeric|digits_between:1,12',
            'positions.*.period_year' => 'required|date_format:Y',
            'positions.*.position_id' => 'required|numeric',
            'positions.*.group_id' => 'required|numeric',
            'positions.*.effective_date' => 'required|date',
            'positions.*.decree' => 'max:160',
            'positions.*.decree_document' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
            'positions.*.type_of_decree' => 'required|numeric',
            'positions.*.decree_number' => 'max:160',
            'positions.*.decree_date' => 'date',
            'positions.*.echelon_description' => 'max:160',
            'positions.*.description' => 'max:160',
            'positions.*.termination_date' => 'required|date',
            'positions.*.termination_decree' => 'max:160',
            'positions.*.type_of_termination_decree' => 'required|numeric',
            'positions.*.termination_decree_number' => 'max:160',
            'positions.*.termination_decree_date' => 'date',
            'positions.*.status' => 'required|numeric',
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
            'positions.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'positions.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'positions.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'positions.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'positions.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'positions.*.position_id.required' => 'Jabatan tidak boleh kosong.',
            'positions.*.position_id.numeric' => 'Jabatan harus berupa angka.',
            'positions.*.group_id.required' => 'Rumpun tidak boleh kosong.',
            'positions.*.group_id.numeric' => 'Rumpun harus berupa angka.',
            'positions.*.effective_date.required' => 'Tanggal efektif jabatan tidak boleh kosong.',
            'positions.*.effective_date.date' => 'Tanggal efektif jabatan harus berupa tanggal.',
            'positions.*.decree.max' => 'SK jabatan tidak boleh lebih dari 160 karakter.',
            'positions.*.decree_document.file' => 'SK jabatan harus berupa file.',
            'positions.*.decree_document.extensions' => 'SK jabatan harus berupa jpg, jpeg atau png.',
            'positions.*.decree_document.max' => 'SK jabatan tidak boleh lebih dari 2MB.',
            'positions.*.type_of_decree.required' => 'Jenis SK jabatan tidak boleh kosong.',
            'positions.*.type_of_decree.numeric' => 'Jenis SK jabatan harus berupa angka.',
            'positions.*.decree_number.max' => 'Nomor SK tidak boleh lebih dari 160 karakter.',
            'positions.*.decree_date.date' => 'Tanggal SK jabatan harus berupa tanggal.',
            'positions.*.echelon_description.max' => 'Keterangan eselon tidak boleh lebih dari 160 karakter.',
            'positions.*.description.max' => 'Keterangan jabatan tidak boleh lebih dari 160 karakter.',
            'positions.*.termination_date.required' => 'Tanggal selesai jabatan tidak boleh kosong.',
            'positions.*.termination_date.date' => 'Tanggal selesai jabatan harus berupa tanggal.',
            'positions.*.termination_decree.max' => 'SK jabatan selesai tidak boleh lebih dari 160 karakter.',
            'positions.*.type_of_termination_decree.required' => 'Jenis SK SLS tidak boleh kosong.',
            'positions.*.type_of_termination_decree.numeric' => 'Jenis SK SLS harus berupa angka.',
            'positions.*.termination_decree_number.max' => 'Nomor SK SLS tidak boleh lebih dari 160 karakter.',
            'positions.*.termination_decree_date.date' => 'Tanggal SK SLS harus berupa tanggal.',
            'positions.*.status.required' => 'Status jabatan tidak boleh kosong.',
            'positions.*.status.numeric' => 'Status jabatan harus berupa angka.',
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
            'positions.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Position.',
                'example' => 3,
            ],
            'positions.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Position.',
                'example' => '2019',
            ],
            'positions.*.position_id' => [
                'description' => 'Refers to the ID Position of Employee Position.',
                'example' => 1,
            ],
            'positions.*.group_id' => [
                'description' => 'Refers to the ID Group of Employee Position.',
                'example' => 1,
            ],
            'positions.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Position.',
                'example' => '2019-10-22',
            ],
            'positions.*.decree' => [
                'description' => 'Refers to the Decree of Employee Position.',
                'example' => 'KEP/50/M.SESNEG/PERS/V/199920 Mei 1999',
            ],
            'positions.*.decree_document' => [
                'description' => 'Refers to the Decree Document of Employee Position.',
                'example' => public_path('/img/logo.svg'),
            ],
            'positions.*.type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Position.',
                'example' => 1,
            ],
            'positions.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Position.',
                'example' => 'KEP/50/M.SESNEG/PERS/V/199920 Mei 1999',
            ],
            'positions.*.decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Position.',
                'example' => '2020-10-20',
            ],
            'positions.*.echelon_description' => [
                'description' => 'Refers to the Echelon Description of Employee Position.',
                'example' => 'Eselon IV',
            ],
            'positions.*.description' => [
                'description' => 'Refers to the Description of Employee Position.',
                'example' => 'Promosi',
            ],
            'positions.*.termination_date' => [
                'description' => 'Refers to the Termination Date of Employee Position.',
                'example' => '2020-10-20',
            ],
            'positions.*.termination_decree' => [
                'description' => 'Refers to the Termination Decree of Employee Position.',
                'example' => 'Kepmensesneg, Nomor 334 Tahun 2020',
            ],
            'positions.*.type_of_termination_decree' => [
                'description' => 'Refers to the Type of Termination Decree of Employee Position.',
                'example' => 1,
            ],
            'positions.*.termination_decree_number' => [
                'description' => 'Refers to the Termination Decree Number of Employee Position.',
                'example' => 'Kepmensesneg, Nomor 334 Tahun 2020',
            ],
            'positions.*.termination_decree_date' => [
                'description' => 'Refers to the Termination Decree Date of Employee Position.',
                'example' => '2020-10-22',
            ],
            'positions.*.status' => [
                'description' => 'Refers to the Status of Employee Position.',
                'example' => 1,
            ],
        ];
    }
}
