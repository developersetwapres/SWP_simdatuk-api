<?php

namespace App\Http\Requests\Disciplinary;

use Illuminate\Foundation\Http\FormRequest;

class CreateDisciplinaryRequest extends FormRequest
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
            'users.*.grade' => 'max:160',
            'users.*.position' => 'max:160',
            'users.*.disciplinary_type_id' => 'required|numeric',
            'users.*.decree_number' => 'max:160',
            'users.*.date_of_decree' => 'date',
            'users.*.start_date' => 'required|date',
            'users.*.end_date' => 'required|date',
            'users.*.authorizing_officer' => 'max:160',
            'users.*.name_of_authorizing_officer' => 'max:160',
            'users.*.description' => 'max:160',
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
            'name.required' => 'Nama tidak boleh kosong.',
            'name.max' => 'Nama tidak boleh lebih dari 160 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.grade.max' => 'Golongan tidak boleh lebih dari 160 karakter.',
            'users.*.position.max' => 'Jabatan tidak boleh lebih dari 160 karakter.',
            'users.*.disciplinary_type_id.required' => 'Jenis hukuman tidak boleh kosong.',
            'users.*.disciplinary_type_id.numeric' => 'Jenis hukuman harus berupa angka.',
            'users.*.decree_number.max' => 'No SK hukuman tidak boleh lebih dari 160 karakter.',
            'users.*.date_of_decree.date' => 'Tanggal SK harus berupa tanggal.',
            'users.*.start_date.required' => 'Tanggal mulai hukuman tidak boleh kosong.',
            'users.*.start_date.date' => 'Tanggal mulai hukuman harus berupa tanggal.',
            'users.*.end_date.required' => 'Tanggal selesai hukuman tidak boleh kosong.',
            'users.*.end_date.date' => 'Tanggal selesai hukuman harus berupa tanggal.',
            'users.*.authorizing_officer.max' => 'Pejabat berwenang tidak boleh lebih dari 160 karakter.',
            'users.*.name_of_authorizing_officer' => 'Nama pejabat berwenang tidak boleh lebih dari 160 karakter.',
            'users.*.description' => 'Uraian tidak boleh lebih dari 160 karakter.',
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
                'description' => 'Refers to the Period Month of Employee Disciplinary.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Disciplinary.',
                'example' => '2020',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee Disciplinary.',
                'example' => 'Hukuman Disiplin Desember 2024',
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of Employee Disciplinary.',
                'example' => 1,
            ],
            'users.*.grade' => [
                'description' => 'Refers to the Golongan of Employee Disciplinary.',
                'example' => 'Penata (III/c)',
            ],
            'users.*.position' => [
                'description' => 'Refers to the Jabatan of Employee Disciplinary.',
                'example' => 'Kepala Subbagian Administrasi',
            ],
            'users.*.disciplinary_type_id' => [
                'description' => 'Refers to the Jenis Hukuman of Employee Disciplinary.',
                'example' => 1,
            ],
            'users.*.decree_number' => [
                'description' => 'Refers to the No SK of Employee Disciplinary.',
                'example' => 'Nomor 112 Tahun 2023',
            ],
            'users.*.date_of_decree' => [
                'description' => 'Refers to the Tanggal SK of Employee Disciplinary.',
                'example' => '2023-10-22',
            ],
            'users.*.start_date' => [
                'description' => 'Refers to the Tanggal Mulai Hukuman of Employee Disciplinary.',
                'example' => '2023-10-22',
            ],
            'users.*.end_date' => [
                'description' => 'Refers to the Tanggal Selesai Hukuman of Employee Disciplinary.',
                'example' => '2024-10-22',
            ],
            'users.*.authorizing_officer' => [
                'description' => 'Refers to the Pejabat Berwenang of Employee Disciplinary.',
                'example' => 'Deputi Bidang Administrasi',
            ],
            'users.*.name_of_authorizing_officer' => [
                'description' => 'Refers to the Nama Pejabat Berwenang of Employee Disciplinary.',
                'example' => 'Sapto Harjono Wahjoe Sedjati, S.Sos., M.A.',
            ],
            'users.*.description' => [
                'description' => 'Refers to the Uraian of Employee Disciplinary.',
                'example' => 'Tidak masuk ke kantor selama 10 hari',
            ],
        ];
    }
}
