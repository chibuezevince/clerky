<?php

namespace Database\Seeders;

use App\Enums\InstitutionType;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = json_decode(file_get_contents(public_path('institutions.json')));
        foreach ($institutions as $country) {
            $type = $this->getType($country->name);
            Institution::create([
                'name' => $country->name,
                'abbreviation' => strtoupper($country->abbreviation),
                'website' => $country->website ?? '',
                'type' => $type,
            ]);
        }
    }

    private function getType(string $name): InstitutionType
    {
        return collect(InstitutionType::cases())->first(fn ($type) => str_contains($name, $type->value)) ?? InstitutionType::University;
    }
}
