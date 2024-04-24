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
            'educations.*.level' => 'numeric|in:1,2,3,4,5,6,7,8,9',
            'educations.*.name' => 'max:160',
            'educations.*.faculty' => 'max:160',
            'educations.*.major' => 'max:160',
            'educations.*.status' => 'numeric|in:1,2,3,4,5',
            'educations.*.year_of_graduation' => 'date_format:Y',
            'educations.*.description' => 'max:160',
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
            'educations.*.level.numeric' => 'Tingkat pendidikan harus berupa angka.',
            'educations.*.level.in' => 'Tingkat pendidikan harus diantara 1,2,3,4,5,6,7,8 atau 9',
            'educations.*.name.max' => 'Nama tidak boleh lebih dari 160 karakter.',
            'educations.*.faculty.max' => 'Nama fakultas tidak boleh lebih dari 160 karakter.',
            'educations.*.major.max' => 'Jurusan tidak boleh lebih dari 160 karakter.',
            'educations.*.status.numeric' => 'Status harus berupa angka.',
            'educations.*.status.in' => 'Status harus diantara 1,2,3,4 atau 5.',
            'educations.*.year_of_graduation.date' => 'Tahun kelulusan harus dengan format YYYY.',
            'educations.*.description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
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
                'description' => 'Refers to the Level of Employee Education. 1=TK / Sederajat, 2=SD / Sederajat, 3=SLTP / Sederajat, 4=SLTA / Sederajat, 5=Diploma I / II, 6=Akademi / Diploma III / Sarjana Muda, 7=Diploma IV / Strata I, 8=Strata II, 9=Strata III',
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
        ];
    }
}
