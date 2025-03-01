<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'name' => 'current_academic_year',
            'value' => '2023-2024'
        ]);

        Setting::create([
            'name' => 'current_semester',
            'value' => '2' // 1 = First, 2 = Second, 3 = Summer
        ]);
    }
}
