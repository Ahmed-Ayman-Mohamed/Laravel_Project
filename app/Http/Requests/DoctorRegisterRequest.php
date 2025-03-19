<?php

namespace App\Http\Requests;

use App\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoctorRegisterRequest extends RegisterRequest
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
            // 'specialization' => ['required', 'string', 'max:255'],
            'myspec' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'year_graduated' => ['nullable', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            'location' => ['nullable', 'string', 'max:255'],
            // 'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'], // Max 2MB
        ]);
    }
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            // 'specialization.required' => 'Specialization is required.',
            'degree.required' => 'Degree is required.',
            'university.required' => 'University name is required.',
            // 'year_graduated.required' => 'Year graduated is required.',
            'year_graduated.digits' => 'Year must be a 4-digit number.',
            'year_graduated.min' => 'Year must be a valid past year.',
            'year_graduated.max' => 'Year cannot be in the future.',
            // 'location.required' => 'Location is required.',
            // 'cv_file.mimes' => 'CV must be a PDF, DOC, or DOCX file.',
            // 'cv_file.max' => 'CV file size must not exceed 2MB.',
        ]);
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationErrorResponse($validator->errors()->toArray())
        );
    }
}
