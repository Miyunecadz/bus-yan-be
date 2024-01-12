<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'company_name' => ['string'],
            'company_address' => ['string'],
            'salary' => ['required', 'string'],
            'job_highlights' => ['required', 'string'],
            'qualifications' => ['required', 'string'],
            'how_to_apply' => ['required', 'string'],
            'about_the_company' => ['string'],
            'image_url' => ['nullable', 'string'],
            'questions' => ['nullable', 'array'],
            'questions.*.description' => ['required', 'string']
        ];
    }
}
