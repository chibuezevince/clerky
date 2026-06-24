<?php

namespace Database\Seeders;

use App\Models\ComplaintTemplate;
use Illuminate\Database\Seeder;

class ComplaintTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $complaints = [
            [
                'name' => 'Chest Pain',
                'slug' => 'chest-pain',
                'is_verified' => true,
            ],

            [
                'name' => 'Fever',
                'slug' => 'fever',
                'is_verified' => true,
            ],

            [
                'name' => 'Fever in a child',
                'slug' => 'fever-in-a-child',
                'is_verified' => true,
            ],

            [
                'name' => 'Shortness of breath',
                'slug' => 'shortness-of-breath',
                'is_verified' => true,
            ],
        ];

        foreach ($complaints as $complaint) {
            ComplaintTemplate::create($complaint);
        }
    }
}
