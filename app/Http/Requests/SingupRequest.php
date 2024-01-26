<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class SingupRequest extends FormRequest
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
            'img' => 'mimes:jpeg,png,jpg,pdf|dimensions:min_width=50,min_height=50|max:10240',
            'username' => 'required|string|max: 255',
            'phone' => 'required|string|min: 11',
            'email' => 'string|email',
            'password' => 'required|string|min: 7|confirmed',
            'password_confirmation' => 'required',
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
