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

    // Add this to ensure consistent academic year format
    protected function getAcademicYearAttribute($value)
    {
        return $value; // Returns the value as stored in DB
    }

    protected function setAcademicYearAttribute($value)
    {
        $this->attributes['academic_year'] = $value; // Stores the value as is
    }

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