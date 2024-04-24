<?php

namespace App\Http\Requests\Recognition;

class CreateRecognitionRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'recognitions.*.period_month' => 'numeric|digits_between:1,12',
            'recognitions.*.period_year' => 'date_format:Y',
            'recognitions.*.name' => 'max:160',
            'recognitions.*.description' => 'max:160',
            'recognitions.*.type_of_decree' => 'numeric',
            'recognitions.*.decree_date' => 'date',
            'recognitions.*.decree_number' => 'max:160',
            'recognitions.*.decree_year' => 'date_format:Y',
            'recognitions.*.awarding_institution' => 'max:160',
            'recognitions.*.date_of_receipt' => 'date',
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
            'recognitions.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'recognitions.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'recognitions.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'recognitions.*.name.max' => 'Nama penghargaan tidak boleh lebih dari 160 karakter.',
            'recognitions.*.description.max' => 'Keterangan penghargaan tidak boleh lebih dari 160 karakter.',
            'recognitions.*.type_of_decree.numeric' => 'Jenis SK harus berupa angka.',
            'recognitions.*.decree_date.date' => 'Tanggal SK harus berupa tanggal.',
            'recognitions.*.decree_number.max' => 'No SK Penghargaan tidak boleh lebih dari 160 karakter.',
            'recognitions.*.decree_year.date_format' => 'Tahun SK harus dengan format YYYY.',
            'recognitions.*.awarding_institution.max' => 'Instansi pemberi penghargaan tidak boleh lebih dari 160 karakter.',
            'recognitions.*.date_of_receipt.date' => 'Tanggal diterima harus berupa tanggal.',
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
            'recognitions.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Recognition.',
                'example' => 3,
            ],
            'recognitions.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Recognition.',
                'example' => '2020',
            ],
            'recognitions.*.name' => [
                'description' => 'Refers to the Name of Employee Recognition.',
                'example' => 'Diklat Komputer Microsoft Excell',
            ],
            'recognitions.*.description' => [
                'description' => 'Refers to the Description of Employee Recognition.',
                'example' => 'Excel',
            ],
            'recognitions.*.type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Recognition.',
                'example' => 1,
            ],
            'recognitions.*.decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Recognition.',
                'example' => '2020-10-22',
            ],
            'recognitions.*.decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Recognition.',
                'example' => 'Keppres Nomor 031/TK/tahun 2008, 17-Aug-08',
            ],
            'recognitions.*.decree_year' => [
                'description' => 'Refers to the Decree Year of Employee Recognition.',
                'example' => '2020',
            ],
            'recognitions.*.awarding_institution' => [
                'description' => 'Refers to the Awarding Institution of Employee Recognition.',
                'example' => 'Setwapres',
            ],
            'recognitions.*.date_of_receipt' => [
                'description' => 'Refers to the Date of Receipt of Employee Recognition.',
                'example' => '2020-10-22',
            ],
        ];
    }
}
