<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class PresentingComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'clerking_section_id' => 2,
                'question' => 'What is the complaint?',
                'field_key' => 'presenting_complaint',
                'input_type' => 'key_value',
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        // 15 questions at this point
    }
}
