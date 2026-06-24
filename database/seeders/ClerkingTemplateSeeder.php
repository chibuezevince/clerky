<?php

namespace Database\Seeders;

use App\Models\ClerkingTemplate;
use Illuminate\Database\Seeder;

class ClerkingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['name' => 'General Medicine / Surgery', 'slug' => 'gen-med-surg', 'description' => 'Standard template for medicine and surgery units'],
            ['name' => 'Paediatrics', 'slug' => 'paediatrics', 'description' => 'Template for paediatric units'],
            ['name' => 'Obstetrics & Gynaecology', 'slug' => 'obs-gyn', 'description' => 'Template for O&G units'],

        ];

        foreach ($templates as $template) {
            ClerkingTemplate::create($template);
        }
    }
}
