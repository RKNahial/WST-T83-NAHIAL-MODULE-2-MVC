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
}
