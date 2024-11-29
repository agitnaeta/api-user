<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserSearchRequest extends FormRequest
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
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'sortBy' => 'nullable|in:name,email,created_at',
        ];
    }

    /**
     * Custom message
     * @return string[]
     */
    public function messages()
    {
        return [
            'sortBy.in' => 'The sortBy value must be one of: name, email, created_at.',
        ];
    }


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Customize the response format when validation fails
        throw new ValidationException($validator, response()->json([
            'status' => 'fail',
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
