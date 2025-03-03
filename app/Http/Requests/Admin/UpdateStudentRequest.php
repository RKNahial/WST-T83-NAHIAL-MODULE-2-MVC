<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->student->user_id . ',id',
                'regex:/^[a-zA-Z0-9._%+-]+@student\.buksu\.edu\.ph$/'
            ],
            'student_id' => 'required|unique:users,student_id,' . $this->student->user_id . ',id',
            'course' => 'required',
            'year_level' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Email must use the @student.buksu.edu.ph domain.'
        ];
    }
}