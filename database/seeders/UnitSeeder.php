<?php

namespace Database\Seeders;

use App\Models\ClerkingTemplate;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Str;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $medSurg = ClerkingTemplate::where('slug', 'gen-med-surg')->first();
        $paeds = ClerkingTemplate::where('slug', 'paediatrics')->first();
        $obsGyn = ClerkingTemplate::where('slug', 'obs-gyn')->first();

        $units = [
            [
                'name' => 'Paediatrics',
                'slug' => Str::slug('Paediatrics'),
                'template' => $paeds,
                'icon' => 'Baby',
                'description' => 'Clerking for patients under 18, including developmental and caregiver-reported histories.',
            ],
            [
                'name' => 'General Medicine',
                'slug' => Str::slug('General Medicine'),
                'template' => $medSurg,
                'icon' => 'HeartPulse',
                'description' => 'Clerking for adult medical admissions across acute and chronic presentations.',
            ],
            [
                'name' => 'General Surgery',
                'slug' => Str::slug('General Surgery'),
                'template' => $medSurg,
                'icon' => 'Scissors',
                'description' => 'Clerking for surgical patients.',
            ],
            [
                'name' => 'Obstetrics & Gynaecology',
                'slug' => Str::slug('Obstetrics & Gynaecology'),
                'template' => $obsGyn,
                'icon' => 'Venus',
                'description' => 'Clerking for surgical admissions and pre-operative assessments.',
            ],
        ];

        foreach ($units as $data) {
            $unit = Unit::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'icon' => $data['icon'],
            ]);

            $unit->templates()->attach($data['template']->id, ['is_default' => true]);
        }
    }
}
