<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use Carbon\Carbon;

class UpdateCurrentSemester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semester:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update current semester based on date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        $day = $now->day;

        // Determine semester based on specific date ranges
        $semester = match(true) {
            // First Semester: August 9 to December 21
            ($month == 8 && $day >= 9) || 
            ($month >= 9 && $month <= 11) || 
            ($month == 12 && $day <= 21) => 1,

            // Second Semester: January 17 to May 20
            ($month == 1 && $day >= 17) || 
            ($month >= 2 && $month <= 4) || 
            ($month == 5 && $day <= 20) => 2,

            // Summer: June 6 to July 12
            ($month == 6 && $day >= 6) || 
            ($month == 7 && $day <= 12) => 3,

            // Default cases for gaps between semesters
            ($month == 12 && $day > 21) || 
            ($month == 1 && $day < 17) => 1, // Consider as First Semester

            ($month == 5 && $day > 20) || 
            ($month == 6 && $day < 6) => 2, // Consider as Second Semester

            ($month == 7 && $day > 12) || 
            ($month == 8 && $day < 9) => 3, // Consider as Summer
        };

        // Determine academic year
        $academicYear = match(true) {
            // First Semester starts in August
            $month >= 8 => $year . '-' . ($year + 1),
            // Second Semester and Summer are part of the previous academic year
            default => ($year - 1) . '-' . $year
        };

        // Update settings
        Setting::updateOrCreate(
            ['name' => 'current_academic_year'],
            ['value' => $academicYear]
        );

        Setting::updateOrCreate(
            ['name' => 'current_semester'],
            ['value' => $semester]
        );

        $this->info('Current semester updated successfully.');
        $this->info("Academic Year: $academicYear");
        $this->info("Semester: " . match($semester) {
            1 => 'First Semester',
            2 => 'Second Semester',
            3 => 'Summer'
        });
    }
}
