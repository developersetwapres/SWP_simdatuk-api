<?php

namespace App\Http\Requests\Assessment;

class CreateAssessmentRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'assessments.*.assessment_date' => 'date',
            'assessments.*.point' => 'required|numeric',
            'assessments.*.organizer' => 'max:160',
            'assessments.*.assessment_document' => 'nullable|file|extensions:jpg,jpeg,png,pdf|max:2048',
            'assessments.*.type' => 'required|numeric|in:1,2,3',
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
            'assessments.*.assessment_date.date' => 'Assessment harus berupa tanggal.',
            'assessments.*.point.required' => 'Assessment tidak boleh kosong.',
            'assessments.*.point.numeric' => 'Assessment harus berupa angka.',
            'assessments.*.organizer.max' => 'Penyelenggara tidak boleh lebih dari 160 karakter.',
            'assessments.*.assessment_document.file' => 'Sertifikat harus berupa file.',
            'assessments.*.assessment_document.extensions' => 'Sertifikat harus berupa jpg, jpeg atau png.',
            'assessments.*.assessment_document.max' => 'Ukuran sertifikat tidak boleh lebih dari 2MB.',
            'assessments.*.type.required' => 'Tipe pelatihan tidak boleh kosong.',
            'assessments.*.type.numeric' => 'Tipe pelatihan harus berupa angka.',
            'assessments.*.type.in' => 'Tipe pelatihan harus diantara 1, 2 atau 3.',
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
            'assessments.*.assessment_date' => [
                'description' => 'Refers to the Assessment Date of Employee Assessment.',
                'example' => '2020-10-22',
            ],
            'assessments.*.point' => [
                'description' => 'Refers to the Point of Employee Assessment.',
                'example' => 10,
            ],
            'assessments.*.organizer' => [
                'description' => 'Refers to the Organizer of Employee Assessment.',
                'example' => 'PPKASN',
            ],
            'assessments.*.assessment_document' => [
                'description' => 'Refers to the Document of Employee Assessment.',
                'example' => public_path('/img/logo.svg'),
            ],
            'assessments.*.type' => [
                'description' => 'Refers to the Type of Employee Assessment. 1=Assessment, 2=Uji Kompetensi, 3=Talent Pool.',
                'example' => 1,
            ],
        ];
    }
}
