<?php

namespace App\Http\Requests\Note;

class CreateNoteRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'notes.*.user_id' => 'required|numeric',
            'notes.*.description' => 'required|max:160',
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
            'notes.*.user_id.required' => 'ID pegawai tidak boleh kosong.',
            'notes.*.user_id.numeric' => 'ID pegawai harus berupa angka.',
            'notes.*.description.required' => 'Catatan tidak boleh kosong',
            'notes.*.description.numeric' => 'Catatan harus berupa angka.',
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
            'notes.*.user_id' => [
                'description' => 'Refers to the User ID of Employee Note.',
                'example' => 1,
            ],
            'notes.*.description' => [
                'description' => 'Refers to the Description of Employee Note.',
                'example' => 'Catatan',
            ],
        ];
    }
}
