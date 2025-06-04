<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteByUserIdRequest extends FormRequest
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
            'notes.*.id' => 'nullable|numeric',
            'notes.*.description' => 'nullable',
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
            'notes.*.id.numeric' => 'Id harus berupa angka.',
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
            'notes.*.id' => [
                'description' => 'Refers to the ID of User Note.',
                'example' => 1,
            ],
            'notes.*.description' => [
                'description' => 'Refers to the Description of User Note.',
                'example' => 'Catatan',
            ],
        ];
    }
}
