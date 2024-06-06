<?php

namespace App\Http\Requests\Leave;

class CreateLeaveRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'leaves.*.grade' => 'nullable',
            'leaves.*.position' => 'nullable',
            'leaves.*.start_date' => 'required|date',
            'leaves.*.end_date' => 'required|date',
            'leaves.*.reason' => 'required|max:160',
            'leaves.*.number' => 'required|max:160',
            'leaves.*.purpose' => 'required|max:160',
            'leaves.*.leave_letter' => 'nullable|file|extensions:jpg,jpeg,png,pdf|max:2048',
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
            'leaves.*.start_date.required' => 'Periode mulai tidak boleh kosong.',
            'leaves.*.start_date.date' => 'Periode mulai harus berupa tanggal.',
            'leaves.*.end_date.required' => 'Periode akhir tidak boleh kosong.',
            'leaves.*.end_date.date' => 'Periode akhir harus berupa tanggal.',
            'leaves.*.reason.required' => 'Alasan tidak boleh kosong.',
            'leaves.*.reason.max' => 'Alasan tidak boleh lebih dari 160 karakter.',
            'leaves.*.number.required' => 'Nomor cuti tidak boleh kosong.',
            'leaves.*.number.max' => 'Nomor cuti tidak boleh lebih dari 160 karakter.',
            'leaves.*.purpose.required' => 'Tujuan tidak boleh kosong.',
            'leaves.*.purpose.max' => 'Tujuan tidak boleh lebih dari 160 karakter',
            'leaves.*.leave_letter.file' => 'Surat cuti harus berupa file.',
            'leaves.*.leave_letter.extensions' => 'Surat cuti harus berupa jpg, jpeg atau png.',
            'leaves.*.leave_letter.max' => 'Ukuran surat cuti tidak boleh lebih dari 2MB.',
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
            'leaves.*.grade' => [
                'description' => 'Refers to the ID Grade of Employee Leave.',
                'example' => 'Penata (III/c)',
            ],
            'leaves.*.position' => [
                'description' => 'Refers to the Position of Employee Leave.',
                'example' => 'Kepala Subbagian, Bagian Protokol, dan Kerumahtanggaan, Deputi Bidang Administrasi',
            ],
            'leaves.*.start_date' => [
                'description' => 'Refers to the Start Date of Employee Leave.',
                'example' => '2020-10-22',
            ],
            'leaves.*.end_date' => [
                'description' => 'Refers to the End Date of Employee Leave.',
                'example' => '2022-10-22',
            ],
            'leaves.*.reason' => [
                'description' => 'Refers to the Reason of Employee Leave.',
                'example' => 'Mudik lebaran',
            ],
            'leaves.*.number' => [
                'description' => 'Refers to the Leave Number of Employee Leave.',
                'example' => 'CT/1000.000.00',
            ],
            'leaves.*.purpose' => [
                'description' => 'Refers to the Purpose of Employee Leave.',
                'example' => 'Semarang',
            ],
            'leaves.*.leave_letter' => [
                'description' => 'Refers to the Leave Letter of Employee Leave.',
                'example' => public_path('/img/logo.svg'),
            ],
        ];
    }
}
