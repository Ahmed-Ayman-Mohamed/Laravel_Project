<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRegisterRequest extends RegisterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'description.string' => 'Description must be a valid text.',
            'description.max' => 'Description should not exceed 1000 characters.',
        ]);
    }
}
