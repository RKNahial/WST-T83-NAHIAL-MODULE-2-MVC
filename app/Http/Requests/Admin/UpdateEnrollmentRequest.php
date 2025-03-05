<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|in:1,2,3',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,dropped,completed'
        ];
    }
}