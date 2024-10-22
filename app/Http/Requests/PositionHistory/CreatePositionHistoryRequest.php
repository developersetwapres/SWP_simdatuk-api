<?php

namespace App\Http\Requests\PositionHistory;

use Illuminate\Foundation\Http\FormRequest;

class CreatePositionHistoryRequest extends FormRequest
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
            'period_month'                       => 'nullable|numeric|digits_between:1,12',
            'period_year'                        => 'nullable|date_format:Y',
            'name'                               => 'nullable|max:160',
            'users.*.user_id'                    => 'required|numeric',
            'users.*.position'                   => 'nullable',
            'users.*.group_id'                   => 'nullable|numeric',
            'users.*.echelon'                    => 'nullable|numeric|exists:position_history_echelons,id',
            'users.*.position_status'            => 'nullable|numeric|in:1,2,3,4',
            'users.*.effective_date'             => 'nullable|date',
            'users.*.decree'                     => 'nullable|max:160',
            'users.*.decree_document'            => 'nullable|file|extensions:jpg,jpeg,png,pdf|max:2048',
            'users.*.type_of_decree'             => 'nullable|numeric',
            'users.*.decree_number'              => 'nullable|max:160',
            'users.*.decree_date'                => 'nullable|date',
            'users.*.termination_date'           => 'nullable|date',
            'users.*.termination_decree'         => 'nullable|max:160',
            'users.*.type_of_termination_decree' => 'nullable|numeric',
            'users.*.termination_decree_number'  => 'nullable|max:160',
            'users.*.termination_decree_date'    => 'nullable|date',
            'users.*.status'                     => 'boolean',
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
            'period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'name.max' => 'Nama nilai prestasi tidak boleh lebih dari 160 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.group_id.numeric' => 'Rumpun harus berupa angka.',
            'users.*.echelon.numeric' => 'Eselon harus berupa angka.',
            'users.*.echelon.exists' => 'Eselon tidak ditemukan.',
            'users.*.position_status.numeric' => 'Keterangan Jabatan harus berupa angka.',
            'users.*.position_status.in' => 'Keterangan Jabatan harus diantara 1, 2, 3 atau 4.',
            'users.*.effective_date.date' => 'Tanggal efektif jabatan harus berupa tanggal.',
            'users.*.decree.max' => 'SK jabatan tidak boleh lebih dari 160 karakter.',
            'users.*.decree_document.file' => 'SK jabatan harus berupa file.',
            'users.*.decree_document.extensions' => 'SK jabatan harus berupa jpg, jpeg atau png.',
            'users.*.decree_document.max' => 'SK jabatan tidak boleh lebih dari 2MB.',
            'users.*.type_of_decree.numeric' => 'Jenis SK jabatan harus berupa angka.',
            'users.*.decree_number.max' => 'Nomor SK tidak boleh lebih dari 160 karakter.',
            'users.*.decree_date.date' => 'Tanggal SK jabatan harus berupa tanggal.',
            'users.*.termination_date.date' => 'Tanggal selesai jabatan harus berupa tanggal.',
            'users.*.termination_decree.max' => 'SK jabatan selesai tidak boleh lebih dari 160 karakter.',
            'users.*.type_of_termination_decree.numeric' => 'Jenis SK SLS harus berupa angka.',
            'users.*.termination_decree_number.max' => 'Nomor SK SLS tidak boleh lebih dari 160 karakter.',
            'users.*.termination_decree_date.date' => 'Tanggal SK SLS harus berupa tanggal.',
            'users.*.status.numeric' => 'Status jabatan harus berupa angka.',
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
                'description' => 'Refers to the Period Month of Employee Position.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Position.',
                'example' => '2019',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee Position.',
                'example' => 'Riwayat Jabatan 2024',
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the ID User of Employee Position.',
                'example' => 1,
            ],
            'users.*.position' => [
                'description' => 'Refers to the ID Position of Employee Position.',
                'example' => 'Staf pada Pembantu Asisten Wakil Presiden Bidang Monitoring dan Kontrol Masyarakat, Asisten Wakil Presiden Bidang Pengawasan (th.1979-1984)',
            ],
            'users.*.group_id' => [
                'description' => 'Refers to the ID Group of Employee Position.',
                'example' => 1,
            ],
            'users.*.echelon' => [
                'description' => 'Refers to the ID Echelon of Employee Position on Position History Echelons. (1=Eselon I, 2=Eselon II, 3=Eselon III, 4=Eselon IV, 5=Pelaksana, 6=Fungsional, 7=Staf)',
                'example' => 1,
            ],
            'users.*.position_status' => [
                'description' => 'Refers to the Position Status of Employee Position. 1=Promosi, 2=Mutasi, 3=Inpassing, 4=Konversi',
                'example' => 1,
            ],
            'users.*.effective_date' => [
                'description' => 'Refers to the Effective Date of Employee Position.',
                'example' => '2019-10-22',
            ],
            'users.*.decree' => [
                'description' => 'Refers to the Decree of Employee Position.',
                'example' => 'Kepmensesneg, Nomor 334 Tahun 2020',
            ],
            'users.*.decree_document' => [
                'description' => 'Refers to the Decree Document of Employee Position.',
                'example' => public_path('/img/logo.svg'),
            ],
            'users.*.type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Position.',
                'example' => 1,
            ],
            'users.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Position.',
                'example' => 'Nomor 334 Tahun 2020',
            ],
            'users.*.decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Position.',
                'example' => '2020-10-20',
            ],
            'users.*.termination_date' => [
                'description' => 'Refers to the Termination Date of Employee Position.',
                'example' => '2020-10-20',
            ],
            'users.*.termination_decree' => [
                'description' => 'Refers to the Termination Decree of Employee Position.',
                'example' => 'Kepmensesneg, Nomor 334 Tahun 2020',
            ],
            'users.*.type_of_termination_decree' => [
                'description' => 'Refers to the Type of Termination Decree of Employee Position.',
                'example' => 1,
            ],
            'users.*.termination_decree_number' => [
                'description' => 'Refers to the Termination Decree Number of Employee Position.',
                'example' => 'Nomor 334 Tahun 2020',
            ],
            'users.*.termination_decree_date' => [
                'description' => 'Refers to the Termination Decree Date of Employee Position.',
                'example' => '2020-10-22',
            ],
            'users.*.status' => [
                'description' => 'Refers to the Status of Employee Position.',
                'example' => 1,
            ],
        ];
    }
}
