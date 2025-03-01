<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'units',
        'semester'
    ];

    // Define relationship with Enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}