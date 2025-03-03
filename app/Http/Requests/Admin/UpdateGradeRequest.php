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
        return [
            'grade' => 'required|numeric|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00'
        ];
    }
}