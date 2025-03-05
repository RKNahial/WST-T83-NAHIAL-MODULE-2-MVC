<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_code' => 'required|string|unique:subjects,code,' . $this->subject->id,
            'subject_name' => 'required|string|max:255',
            'units' => 'required|integer|min:1|max:6',
            'semester' => 'required|in:1,2,3'
        ];
    }
}