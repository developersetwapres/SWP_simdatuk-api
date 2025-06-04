<?php

namespace App\Http\Requests\TrainingHistory;

use Illuminate\Foundation\Http\FormRequest;

class CreateTrainingHistoryRequest extends FormRequest
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
            'name' => 'required|max:512',
            'reference_number' => 'required|max:160',
            'level' => 'nullable',
            'group_id' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'duration' => 'nullable|numeric',
            'organizer' => 'nullable|max:512',
            'link' => 'nullable|url',
            'type' => 'required|numeric|in:1,2,3',
            'description' => 'nullable|max:255',
            'users.*.user_id' => 'required|numeric|exists:users,id',
            'users.*.certificate' => 'nullable|file|extensions:jpg,jpeg,png,pdf|max:2048',
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
            'name.required' => 'Nama diklat tidak boleh kosong.',
            'name.max' => 'Nama diklat tidak boleh lebih dari 512 karakter.',
            'reference_number.required' => 'No surat perintah tidak boleh kosong.',
            'reference_number.max' => 'No surat perintah tidak boleh lebih dari 160 karakter.',
            'start_date.required' => 'Tanggal pelaksanaan tidak boleh kosong.',
            'start_date.date' => 'Tanggal pelaksanaan harus berupa tanggal.',
            'end_date.date' => 'Tanggal akhir pelaksanaan harus berupa tanggal.',
            'duration.numeric' => 'Durasi pelatihan harus berupa angka.',
            'organizer.max' => 'Penyelenggara tidak boleh lebih dari 512 karakter.',
            'link.url' => 'Format link tidak sesuai.',
            'type.required' => 'Tipe pelatihan tidak boleh kosong.',
            'type.numeric' => 'Tipe pelatihan harus berupa angka.',
            'type.in' => 'Tipe pelatihan harus diantara 1, 2 atau 3.',
            'description.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
            'users.*.user_id.required' => 'User ID tidak boleh kosong.',
            'users.*.user_id.numeric' => 'User ID harus berupa angka.',
            'users.*.user_id.exists' => 'User ID tidak terdaftar!',
            'users.*.certificate.file' => 'Sertifikat harus berupa file.',
            'users.*.certificate.extensions' => 'Sertifikat harus berupa jpg, jpeg atau png.',
            'users.*.certificate.max' => 'Ukuran sertifikat tidak boleh lebih dari 2MB.',
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
                'description' => 'Refers to the Period Month of Employee Training.',
                'example' => 3,
            ],
            'period_year' => [
                'description' => 'Refers to the Period Year of Employee Training.',
                'example' => '2020',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee Training.',
                'example' => 'Sepadya tahun 1994',
            ],
            'reference_number' => [
                'description' => 'Refers to the Reference Number of Employee Training.',
                'example' => '13936/PPKASN/09/2021',
            ],
            'level' => [
                'description' => 'Refers to the ID Level of Employee Training.',
                'example' => 1,
            ],
            'group_id' => [
                'description' => 'Refers to the ID Groups of Employee Training.',
                'example' => 1,
            ],
            'start_date' => [
                'description' => 'Refers to the Start Date of Employee Training.',
                'example' => '2020-10-22',
            ],
            'end_date' => [
                'description' => 'Refers to the End Date of Employee Training.',
                'example' => '2020-10-23',
            ],
            'duration' => [
                'description' => 'Refers to the Duration of Employee Training.',
                'example' => 10,
            ],
            'organizer' => [
                'description' => 'Refers to the Organizer of Employee Training.',
                'example' => 'PPKASN',
            ],
            'link' => [
                'description' => 'Refers to the Link of Employee Training.',
                'example' => 'https://google.com',
            ],
            'type' => [
                'description' => 'Refers to the Type of Employee Training. 1=Struktural, 2=Fungsional, 3=Teknis.',
                'example' => 1,
            ],
            'description' => [
                'description' => 'Refers to the Description of Employee Training.',
                'example' => "Pelatihan lainnya",
            ],
            'users.*.user_id' => [
                'description' => 'Refers to the User ID of List Employee Training.',
                'example' => 1,
            ],
            'users.*.certificate' => [
                'description' => 'Refers to the Certificate of List Employee Training.',
                'example' => public_path('/img/logo.svg'),
            ],
        ];
    }
}
