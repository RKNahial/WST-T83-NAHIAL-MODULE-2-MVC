<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'semester',
        'academic_year',
        'status'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

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
}