<?php

namespace App\Http\Requests\Education;

class CreateEducationRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'educations.*.level' => 'required|numeric|in:1,2,3,4,5,6,7,8',
            'educations.*.name' => 'required|max:160',
            'educations.*.faculty' => 'max:160',
            'educations.*.major' => 'max:160',
            'educations.*.status' => 'required|numeric|in:1,2,3,4,5',
            'educations.*.year_of_graduation' => 'required|date_format:Y',
            'educations.*.description' => 'max:160',
            'educations.*.degree_document' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
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
            'educations.*.level.required' => 'Tingkat pendidikan tidak boleh kosong.',
            'educations.*.level.numeric' => 'Tingkat pendidikan harus berupa angka.',
            'educations.*.level.in' => 'Tingkat pendidikan harus diantara 1,2,3,4,5,6,7,8 atau 9',
            'educations.*.name.requird' => 'Nama tidak boleh kosong.',
            'educations.*.name.max' => 'Nama tidak boleh lebih dari 160 karakter.',
            'educations.*.faculty.max' => 'Nama fakultas tidak boleh lebih dari 160 karakter.',
            'educations.*.major.max' => 'Jurusan tidak boleh lebih dari 160 karakter.',
            'educations.*.status.required' => 'Status tidak boleh kosong.',
            'educations.*.status.numeric' => 'Status harus berupa angka.',
            'educations.*.status.in' => 'Status harus diantara 1,2,3,4 atau 5.',
            'educations.*.year_of_graduation.required' => 'Tahun kelulusan tidak boleh kosong.',
            'educations.*.year_of_graduation.date_format' => 'Tahun kelulusan harus dengan format YYYY.',
            'educations.*.description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
            'educations.*.degree_document.file' => 'Ijazah harus berupa file.',
            'educations.*.degree_document.extensions' => 'Ijazah harus berupa jpg, jpeg atau png.',
            'educations.*.degree_document.max' => 'Ukuran ijazah tidak boleh lebih dari 2MB.',
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
            'educations.*.level' => [
                'description' => 'Refers to the Level of Employee Education. 1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat, 4=Diploma I/II, 5=Akademik/D3/S.Muda, 6=Diploma IV/Strata I, 7=Strata II, 8=Strata III',
                'example' => 1,
            ],
            'educations.*.name' => [
                'description' => 'Refers to the Name of Employee Education.',
                'example' => 'Universitas Indonesia',
            ],
            'educations.*.faculty' => [
                'description' => 'Refers to the Faculty of Employee Education.',
                'example' => 'Fakultas Ilmu Komputer',
            ],
            'educations.*.major' => [
                'description' => 'Refers to the Major of Employee Education.',
                'example' => 'Teknik Informatika',
            ],
            'educations.*.status' => [
                'description' => 'Refers to the Status of Employee Education. 1=Lulus, 2=DO, 3=Aktif, 4=Non-Aktif, 5=Mengundurkan Diri',
                'example' => 1,
            ],
            'educations.*.year_of_graduation' => [
                'description' => 'Refers to the Year of Graduation of Employee Education.',
                'example' => '1994',
            ],
            'educations.*.description' => [
                'description' => 'Refers to the Description of Employee Education.',
                'example' => 'Keterangan',
            ],
            'educations.*.degree_document' => [
                'description' => 'Refers to the Degree Document of Employee Education.',
                'example' => public_path('/img/logo.svg'),
            ],
        ];
    }
}
