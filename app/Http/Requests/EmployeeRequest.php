<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'id_number' => ['required', 'string'],
            'full_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'contact_number' => ['required', 'string'],
            'profile_url' => ['nullable', 'string'],
            'employee_type' => ['required', 'string']
        ];
    }
}
