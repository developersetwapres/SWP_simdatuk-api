<?php

namespace App\Http\Requests\Training;

class CreateTrainingRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'trainings.*.period_month' => 'required|numeric|digits_between:1,12',
            'trainings.*.period_year' => 'required|date_format:Y',
            'trainings.*.name' => 'required|max:160',
            'trainings.*.reference_number' => 'required|max:160',
            'trainings.*.level' => 'max:160',
            'trainings.*.start_date' => 'required|date',
            'trainings.*.duration' => 'numeric',
            'trainings.*.organizer' => 'max:160',
            'trainings.*.certificate' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
            'trainings.*.type' => 'required|numeric|in:1,2,3',
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
            'trainings.*.period_month.required' => 'Bulan periode riwayat tidak boleh kosong.',
            'trainings.*.period_month.numeric' => 'Bulan periode riwayat harus berupa angka.',
            'trainings.*.period_month.digits_between' => 'Bulan periode riwayat harus diantara 1 hingga 12.',
            'trainings.*.period_year.required' => 'Tahun periode riwayat tidak boleh kosong.',
            'trainings.*.period_year.date_format' => 'Tahun periode riwayat harus dengan format YYYY.',
            'trainings.*.name.required' => 'Nama diklat tidak boleh kosong.',
            'trainings.*.name.max' => 'Nama diklat tidak boleh lebih dari 160 karakter.',
            'trainings.*.reference_number.required' => 'No surat perintah tidak boleh kosong.',
            'trainings.*.reference_number.max' => 'No surat perintah tidak boleh lebih dari 160 karakter.',
            'trainings.*.level.max' => 'Jenjang tidak boleh lebih dari 160 karakter.',
            'trainings.*.start_date.required' => 'Tanggal pelaksanaan tidak boleh kosong.',
            'trainings.*.start_date.date' => 'Tanggal pelaksanaan harus berupa tanggal.',
            'trainings.*.duration.numeric' => 'Durasi pelatihan harus berupa angka.',
            'trainings.*.organizer.max' => 'Penyelenggara tidak boleh lebih dari 160 karakter.',
            'trainings.*.certificate.file' => 'Sertifikat harus berupa file.',
            'trainings.*.certificate.extensions' => 'Sertifikat harus berupa jpg, jpeg atau png.',
            'trainings.*.certificate.max' => 'Ukuran sertifikat tidak boleh lebih dari 2MB.',
            'trainings.*.type.required' => 'Tipe pelatihan tidak boleh kosong.',
            'trainings.*.type.numeric' => 'Tipe pelatihan harus berupa angka.',
            'trainings.*.type.in' => 'Tipe pelatihan harus diantara 1, 2 atau 3.',
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
            'trainings.*.period_month' => [
                'description' => 'Refers to the Period Month of Employee Training.',
                'example' => 3,
            ],
            'trainings.*.period_year' => [
                'description' => 'Refers to the Period Year of Employee Training.',
                'example' => '2020',
            ],
            'trainings.*.name' => [
                'description' => 'Refers to the Name of Employee Training.',
                'example' => 'Sepadya tahun 1994',
            ],
            'trainings.*.reference_number' => [
                'description' => 'Refers to the Reference Number of Employee Training.',
                'example' => 'No 123',
            ],
            'trainings.*.level' => [
                'description' => 'Refers to the Level of Employee Training.',
                'example' => 'Diklat PIM Tk.III',
            ],
            'trainings.*.start_date' => [
                'description' => 'Refers to the Start Date of Employee Training.',
                'example' => '2020-10-22',
            ],
            'trainings.*.duration' => [
                'description' => 'Refers to the Duration of Employee Training.',
                'example' => 10,
            ],
            'trainings.*.organizer' => [
                'description' => 'Refers to the Organizer of Employee Training.',
                'example' => 'PPKASN',
            ],
            'trainings.*.certificate' => [
                'description' => 'Refers to the Certificate of Employee Training.',
                'example' => public_path('/img/logo.svg'),
            ],
            'trainings.*.type' => [
                'description' => 'Refers to the Type of Employee Training. 1=Struktural, 2=Fungsional, 3=Teknis.',
                'example' => 1,
            ],
        ];
    }
}
