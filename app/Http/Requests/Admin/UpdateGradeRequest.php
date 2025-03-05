<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedGrades = [
            '1.00', '1.25', '1.50', '1.75',
            '2.00', '2.25', '2.50', '2.75',
            '3.00', '5.00'
        ];

        return [
            'grade' => 'required|numeric|in:' . implode(',', $allowedGrades),
        ];
    }

    public function messages(): array
    {
        return [
            'grade.in' => 'Invalid grade value. Please select a valid grade.',
            'grade.required' => 'Please select a grade.',
            'grade.numeric' => 'Grade must be a number.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new \Illuminate\Validation\ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl())
                    ->with('edit_grade_id', $this->route('grade')->id);
    }
}