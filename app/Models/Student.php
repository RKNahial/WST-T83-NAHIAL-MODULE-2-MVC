<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'student_id',
        'course',
        'year_level'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateGWA()
    {
        // Get all grades for enrolled and completed subjects only for the current semester
        $grades = $this->enrolledSubjects()
            ->whereIn('status', ['enrolled', 'completed'])
            ->where('academic_year', $this->current_academic_year) // Add your academic year field
            ->where('semester', $this->current_semester) // Add your semester field
            ->with('grade')
            ->whereHas('grade')
            ->get();

        if ($grades->isEmpty()) {
            return 0;
        }

        $totalGrade = 0;
        $subjectCount = 0;

        foreach ($grades as $subject) {
            if ($subject->grade) {
                $totalGrade += $subject->grade->grade;
                $subjectCount++;
            }
        }

        return $subjectCount > 0 ? number_format($totalGrade / $subjectCount, 2) : 0;
    }

    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
}
