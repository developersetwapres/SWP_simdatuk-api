<?php

namespace App\Http\Requests\RecognitionHistory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecognitionHistoryRequest extends FormRequest
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
            'recognition_id' => 'required|numeric',
            'description' => 'max:160',
            'type_of_decree' => 'required|numeric',
            'decree_date' => 'required|date',
            'decree_number' => 'required|max:160',
            'decree_year' => 'date_format:Y',
            'awarding_institution' => 'max:160',
            'date_of_receipt' => 'date',
            'users.*.id' => 'numeric|nullable',
            'users.*.user_id' => 'required|numeric',
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
            'recognition_id.required' => 'Penghargaan tidak boleh kosong.',
            'recognition_id.numeric' => 'Penghargaan harus berupa angka.',
            'description.max' => 'Keterangan penghargaan tidak boleh lebih dari 160 karakter.',
            'type_of_decree.required' => 'Jenis SK tidak boleh kosong.',
            'type_of_decree.numeric' => 'Jenis SK harus berupa angka.',
            'decree_date.required' => 'Tanggal SK tidak boleh kosong.',
            'decree_date.date' => 'Tanggal SK harus berupa tanggal.',
            'decree_number.required' => 'No SK Penghargaan tidak boleh kosong.',
            'decree_number.max' => 'No SK Penghargaan tidak boleh lebih dari 160 karakter.',
            'decree_year.date_format' => 'Tahun SK harus dengan format YYYY.',
            'awarding_institution.max' => 'Instansi pemberi penghargaan tidak boleh lebih dari 160 karakter.',
            'date_of_receipt.date' => 'Tanggal diterima harus berupa tanggal.',
            'users.*.id.numeric' => 'ID harus berupa angka.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
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
                'description' => 'Refers to the Period Month of Employee Recognition.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Recognition.',
                'example' => '2020',
            ],
            'recognition_id' => [
                'description' => 'Refers to the ID of List Recognition.',
                'example' => 1,
            ],
            'description' => [
                'description' => 'Refers to the Description of Employee Recognition.',
                'example' => 'Excel',
            ],
            'type_of_decree' => [
                'description' => 'Refers to the Type of Decree of Employee Recognition.',
                'example' => 1,
            ],
            'decree_date' => [
                'description' => 'Refers to the Decree Date of Employee Recognition.',
                'example' => '2020-10-22',
            ],
            'decree_number' => [
                'description' => 'Refers to the Decree Number of Employee Recognition.',
                'example' => 'Keppres Nomor 031/TK/tahun 2008, 17-Aug-08',
            ],
            'decree_year' => [
                'description' => 'Refers to the Decree Year of Employee Recognition.',
                'example' => '2020',
            ],
            'awarding_institution' => [
                'description' => 'Refers to the Awarding Institution of Employee Recognition.',
                'example' => 'Setwapres',
            ],
            'date_of_receipt' => [
                'description' => 'Refers to the Date of Receipt of Employee Recognition.',
                'example' => '2020-10-22',
            ],
            'users.*.id' => [
                'description' => 'Refers to the ID of List Employee Recognition.',
                'example' => 1,
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of List Employee Recognition.',
                'example' => 1,
            ],
        ];
    }
}
