<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class AnswersRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'img' => 'mimes:jpeg,png,jpg,pdf|dimensions:min_width=50,min_height=50|max:10240',
            'first_step' => 'exists:first_steps,id',
            'second_step' => 'date_format:H:i',
            'third_steps' => 'exists:third_steps,id'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => 0,
                'errors' => $validator->errors(),

            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
