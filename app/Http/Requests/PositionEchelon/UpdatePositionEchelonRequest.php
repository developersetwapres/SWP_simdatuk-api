<?php

namespace App\Http\Requests\PositionEchelon;

class UpdatePositionEchelonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'position_echelons.*.id' => 'numeric|nullable',
            'position_echelons.*.echelon_id' => 'numeric',
            'position_echelons.*.available' => 'numeric',
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
            'position_echelons.*.echelon_id.numeric' => 'Echelon ID harus berupa angka.',
            'position_echelons.*.available.numeric' => 'Available harus berupa angka.',
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
            'position_echelons.*.echelon_id' => [
                'description' => 'Refers to the ID of Echelon.',
                'example' => 1,
            ],
            'position_echelons.*.available' => [
                'description' => 'Refers to the Available count for fill of Position.',
                'example' => 1,
            ],
        ];
    }
}
