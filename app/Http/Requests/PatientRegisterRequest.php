<?php

namespace App\Http\Requests;

use App\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PatientRegisterRequest extends RegisterRequest
{
    use ApiResponseTrait;
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
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors()->toArray())
        );
    }
}
