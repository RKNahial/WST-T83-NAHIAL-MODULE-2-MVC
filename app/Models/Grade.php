<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'grade',
        'semester'
    ];

    /**
     * Get the enrollment that owns the grade.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
