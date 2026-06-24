<?php

namespace Database\Seeders;

use App\Models\AcademicLevel;
use Illuminate\Database\Seeder;

class AcademicLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            '100' => '100 Level',
            '200' => '200 Level',
            '300' => '300 Level',
            '400' => '400 Level',
            '500' => '500 Level',
            '600' => '600 Level',
        ];

        foreach ($levels as $level) {
            AcademicLevel::create([
                'name' => $level,
                'label' => $level,
            ]);
        }
    }
}
