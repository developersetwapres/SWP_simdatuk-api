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
            'leaves.*.grade_id' => 'numeric',
            'leaves.*.position' => 'numeric',
            'leaves.*.start_date' => 'date',
            'leaves.*.end_date' => 'date',
            'leaves.*.reason' => 'max:160',
            'leaves.*.number' => 'max:160',
            'leaves.*.purpose' => 'max:160',
            'leaves.*.leave_letter' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
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
            'leaves.*.grade_id.numeric' => 'Golongan harus berupa angka.',
            'leaves.*.position.numeric' => 'Jabatan harus berupa angka.',
            'leaves.*.start_date.date' => 'Periode mulai harus berupa tanggal.',
            'leaves.*.end_date.date' => 'Periode akhir harus berupa tanggal.',
            'leaves.*.reason.max' => 'Alasan tidak boleh lebih dari 160 karakter.',
            'leaves.*.number.max' => 'Nomor cuti tidak boleh lebih dari 160 karakter.',
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
            'leaves.*.grade_id' => [
                'description' => 'Refers to the ID Grade of Employee Leave.',
                'example' => 1,
            ],
            'leaves.*.position' => [
                'description' => 'Refers to the Position of Employee Leave.',
                'example' => 1,
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
