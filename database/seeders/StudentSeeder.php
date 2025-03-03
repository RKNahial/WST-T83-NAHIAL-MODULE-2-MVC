<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'BSIT',
            'BSCS',
            'BSIS',
            'BPA',
            'BAEcon',
            'BAEL',
            'BAPhil',
            'BASoc',
            'BSCD',
            'BSDevCom',
            'BSMath',
            'BSBioTech',
            'BSEnvSci',
            'BSBAFM',
            'BSHM',
            'BSAutoTech',
            'BSElecTech',
            'BSEMCDA',
            'BSEMCD',
        ];
        $yearLevels = [1, 2, 3, 4];
        
        // Starting student ID
        $baseStudentId = 220011056;

        for ($i = 0; $i < 50; $i++) {
            // Generate student ID by incrementing the base
            $studentId = $baseStudentId + $i;
            
            // Generate email using student ID
            $email = $studentId . '@student.buksu.edu.ph';
            
            // Generate name
            $fullName = fake()->name();

            // Create user first
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            // Create corresponding student record
            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'name' => $fullName,
                'email' => $email,
                'course' => $courses[array_rand($courses)],
                'year_level' => $yearLevels[array_rand($yearLevels)],
                'is_archived' => false,
            ]);
        }
    }
}