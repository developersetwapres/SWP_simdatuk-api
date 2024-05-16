<?php

namespace App\Http\Requests\GradeHistory;

use Illuminate\Foundation\Http\FormRequest;

class CreateGradeHistoryRequest extends FormRequest
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
            'users.*.grade_id' => 'required|numeric',
            'users.*.effective_date' => 'required|date',
            'users.*.decree_name' => 'max:160',
            'users.*.decree_document' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
            'users.*.type_of_decree' => 'numeric',
            'users.*.decree_number' => 'max:160',
            'users.*.decree_date' => 'date',
            'users.*.description' => 'max:160',
            'users.*.status' => 'numeric',
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
            'name.required' => 'Nama penghargaan tidak boleh kosong.',
            'name.max' => 'Nama penghargaan tidak boleh lebih dari 160 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.grade_id.required' => 'Golongan tidak boleh kosong.',
            'users.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'users.*.effective_date.required' => 'Tanggal efektif golongan tidak boleh kosong.',
            'users.*.effective_date.date' => 'Tanggal efektif golongan harus berupa tanggal.',
            'users.*.decree_name.max' => 'SK golongan tidak boleh lebih dari 160 karakter.',
            'users.*.decree_document.file' => 'SK golongan harus berupa file.',
            'users.*.decree_document.extensions' => 'SK golongan harus berupa jpg, jpeg atau png.',
            'users.*.decree_document.max' => 'SK golongan tidak boleh lebih dari 2MB',
            'users.*.type_of_decree.numeric' => 'Jenis SK golongan harus berupa angka.',
            'users.*.decree_number.max' => 'Nomor SK golongan tidak beloh lebih dari 160 karakter.',
            'users.*.decree_date.date' => 'Tanggal SK golongan harus berupa tanggal.',
            'users.*.description.max' => 'Keterangan golongan tidak boleh lebih dari 160 karakter.',
            'users.*.status.numeric' => 'Status golongan harus berupa angka.',
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
                'description' => 'Refers to the Period Month of Employee Grade.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Grade.',
                'example' => '2010',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee Grade.',
                'example' => 'Riwayat Desember 2023',
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of List Employee Recognition.',
                'example' => 1,
            ],
            'users.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Grade.',
                'example' => 1,
            ],
            'users.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Grade.',
                'example' => '2020-10-22',
            ],
            'users.*.decree_name' => [
                'description' => 'Refers to the Decree Name of Employee Grade.',
                'example' => 'Kepmensesneg, Nomor 50 Tahun 2008, 06-March-2007',
            ],
            'users.*.decree_document' => [
                'description' => 'Refers to the Decree Document of Employee Grade.',
                'example' => public_path('/img/logo.svg'),
            ],
            'users.*.type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Grade.',
                'example' => 1,
            ],
            'users.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Grade.',
                'example' => 'Nomor 50 Tahun 2008',
            ],
            'users.*.decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Grade.',
                'example' => '2020-10-22',
            ],
            'users.*.description' => [
                'description' => 'Refers to the Description of Employee Grade.',
                'example' => 'Kenaikan Pangkat Reguler',
            ],
            'users.*.status' => [
                'description' => 'Refers to the Status of Employee Grade.',
                'example' => 1,
            ],
        ];
    }
}
