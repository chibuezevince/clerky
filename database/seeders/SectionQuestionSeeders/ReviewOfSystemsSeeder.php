<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class ReviewOfSystemsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'clerking_section_id' => 17,
                'question' => 'General — Any fever, weight loss, fatigue, or night sweats?',
                'field_key' => 'ros_general',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Respiratory — Any cough, breathlessness, wheeze, or sputum?',
                'field_key' => 'ros_respiratory',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Cardiovascular — Any chest pain, palpitations, or ankle swelling?',
                'field_key' => 'ros_cardiovascular',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Gastrointestinal — Any nausea, vomiting, diarrhoea, or abdominal pain?',
                'field_key' => 'ros_gastrointestinal',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Genitourinary — Any dysuria, frequency, or vaginal/urethral discharge?',
                'field_key' => 'ros_genitourinary',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Central Nervous System — Any headaches, dizziness, seizures, or altered sensation?',
                'field_key' => 'ros_cns',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Musculoskeletal — Any joint pain, swelling, or stiffness?',
                'field_key' => 'ros_msk',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
            [
                'clerking_section_id' => 17,
                'question' => 'Skin — Any rashes, itching, or changes in skin colour?',
                'field_key' => 'ros_skin',
                'input_type' => 'textarea',
                'max_char' => 1000,
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }
    }
}
