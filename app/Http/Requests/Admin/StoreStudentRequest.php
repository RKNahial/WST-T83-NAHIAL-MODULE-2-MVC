<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:255|unique:students',
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@student\.buksu\.edu\.ph$/'
            ],
            'course' => 'required|string|max:255',
            'year_level' => 'required|in:1,2,3,4',
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Email must use the @student.buksu.edu.ph domain.'
        ];
    }
}