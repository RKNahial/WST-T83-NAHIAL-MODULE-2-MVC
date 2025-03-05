<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_input' => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ];
    }
}