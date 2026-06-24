<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class AnthropometrySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_anthropometry_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 20,
                'question' => "What is the child's current weight (kg)?",
                'field_key' => 'weight',
                'input_type' => 'number',
            ],
            [
                'clerking_section_id' => 20,
                'question' => "What is the child's current height or length (cm)?",
                'field_key' => 'height',
                'input_type' => 'number',
            ],
            [
                'clerking_section_id' => 20,
                'question' => "What is the child's head circumference (cm)?",
                'field_key' => 'head_circumference',
                'input_type' => 'number',
            ],
            [
                'clerking_section_id' => 20,
                'question' => "What is the child's Mid-Upper Arm Circumference (MUAC) (cm)?",
                'field_key' => 'muac',
                'input_type' => 'number',
            ],
            [
                'clerking_section_id' => 20,
                'question' => 'Is there any visible wasting (muscle loss) or signs of malnutrition?',
                'field_key' => 'malnutrition_signs',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'mild_wasting' => 'Mild wasting',
                    'severe_wasting' => 'Severe wasting',
                    'oedema' => 'Oedema (kwashiorkor)',
                    'both' => 'Wasting and oedema',
                ],
            ],
            [
                'clerking_section_id' => 20,
                'question' => 'What is the child\'s BMI-for-age percentile (if applicable)?',
                'field_key' => 'bmi_percentile',
                'input_type' => 'select',
                'options' => [
                    'normal' => 'Normal (5th - 85th percentile)',
                    'overweight' => 'Overweight (85th - 95th percentile)',
                    'obese' => 'Obese (> 95th percentile)',
                    'underweight' => 'Underweight (< 5th percentile)',
                    'not_calculated' => 'Not calculated',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_anthropometry_id' => $latest->id,
        ]);
    }
}
