<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class SystemicExaminationSeeder extends Seeder {
    public function run(): void {
        $questions = [
            [
                'clerking_section_id' => 19,
                'question' => 'Respiratory System examination findings:',
                'field_key' => 'systemic_exam_respiratory',
                'input_type' => 'textarea',
                'max_char' => 2000,
            ],
            [
                'clerking_section_id' => 19,
                'question' => 'Cardiovascular System examination findings:',
                'field_key' => 'systemic_exam_cardiovascular',
                'input_type' => 'textarea',
                'max_char' => 2000,
            ],
            [
                'clerking_section_id' => 19,
                'question' => 'Gastrointestinal / Abdominal examination findings:',
                'field_key' => 'systemic_exam_gi',
                'input_type' => 'textarea',
                'max_char' => 2000,
            ],
            [
                'clerking_section_id' => 19,
                'question' => 'Central Nervous System examination findings:',
                'field_key' => 'systemic_exam_cns',
                'input_type' => 'textarea',
                'max_char' => 2000,
            ],
            [
                'clerking_section_id' => 19,
                'question' => 'Musculoskeletal System examination findings:',
                'field_key' => 'systemic_exam_msk',
                'input_type' => 'textarea',
                'max_char' => 2000,
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }
    }
}
