<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year',
        'semester',
        'status'
    ];

    // Define relationship with Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Define relationship with Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the grade associated with the enrollment.
     */
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}