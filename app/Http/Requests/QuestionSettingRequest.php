<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class QuestionSettingRequest extends FormRequest
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
            'row' => 'required|integer',
            'digit' => 'required|integer|min:-128|max:127',
            'speed' => 'required|date_format:H:i',
            'round' => 'required|integer',
            'completed' => 'required|boolean',
            'exam' => 'required|boolean',
            'exam_time' => 'required|date_format:H:i',
            'exam_name' => 'required|string',
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
