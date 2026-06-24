<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class GeneralExaminationSeeder extends Seeder {
    public function run(): void {
        SectionQuestion::create([
            'clerking_section_id' => 18,
            'question' => 'Document the general examination findings:',
            'field_key' => 'general_examination',
            'input_type' => 'textarea',
            'max_char' => 2000,
        ]);
    }
}
