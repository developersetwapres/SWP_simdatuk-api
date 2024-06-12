<?php

namespace App\Http\Requests\CreditScore;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditScoreRequest extends FormRequest
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
            'position' => 'nullable|string|max:160',
            'period' => 'required|numeric|between:1,5',
            'year' => 'required|date_format:Y',
            'last_credit_score' => 'between:1.00,100.00',
            'user_id' => 'required|integer',
        ];
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return  [
            'position.string' => 'Position harus dalam bentuk string',
            'position.max' => 'Position tidak boleh lebih dari 160 karakter',
            'period.required' => 'Period harus diisi',
            'period.numeric' => 'Period harus berupa angka',
            'period.in' => 'Period harus diantara 1, 2, 3, 4, dan 5',
            'year.required' => 'Year harus diisi',
            'year.numeric' => 'Year dalam format tahun',
            'last_credit_score.required' => 'Last Credit Score harus diantara 1.00 hingga 100.00',
            'user_id.required' => 'User id harus diisi',
            'user_id.integer' => 'User id harus dalam bentuk integer',
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
            'position' => [
                'description' => 'Refers to Employee position',
                'example' => 'Ahli Muda'
            ],
            'period' => [
                'description' => 'Refers to Employee credit score period',
                'example' => '5'
            ],
            'year' => [
                'description' => 'Refers to Employee position and credit score year',
                'example' => '2024'
            ],
            'last_credit_score' => [
                'description' => 'Refers to Employee last credit score',
                'example' => '80.00',
            ],
            'user_id' => [
                'description' => 'Refers to Employee user id',
                'example' => '1',
            ]
        ];
    }
}
