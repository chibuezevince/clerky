<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class HistoryOfPresentingComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'clerking_section_id' => 3,
                'question' => 'What have you done so far for this illness?',
                'field_key' => 'care_actions_taken',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Have you taken any medications for this illness?',
                'field_key' => 'care_medications_taken',
                'input_type' => 'boolean',
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $medicationsTakenId = SectionQuestion::where('field_key', 'care_medications_taken')->first()->id;

        $medicationFollowUps = [
            [
                'clerking_section_id' => 3,
                'question' => 'If yes, what medications have you taken?',
                'field_key' => 'care_medication_details',
                'input_type' => 'text',
                'depends_on_section_question_id' => $medicationsTakenId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Were these medications prescribed by a doctor?',
                'field_key' => 'care_prescribed',
                'input_type' => 'boolean',
                'depends_on_section_question_id' => $medicationsTakenId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Did the treatment improve the symptoms?',
                'field_key' => 'care_response_to_treatment',
                'depends_on_section_question_id' => $medicationsTakenId,
                'depends_on_answer' => 'yes',
                'input_type' => 'select',
                'options' => [
                    'improved' => 'Improved',
                    'no_change' => 'No change',
                    'worsened' => 'Worsened',
                ],
            ],
        ];

        foreach ($medicationFollowUps as $question) {
            SectionQuestion::create($question);
        }

        $remainingQuestions = [
            [
                'clerking_section_id' => 3,
                'question' => 'Have you visited any hospital, clinic, or pharmacy for this illness?',
                'field_key' => 'care_healthcare_visit',
                'input_type' => 'boolean',
            ],
        ];

        foreach ($remainingQuestions as $question) {
            SectionQuestion::create($question);
        }

        $healthcareVisitId = SectionQuestion::where('field_key', 'care_healthcare_visit')->first()->id;

        $healthcareFollowUps = [
            [
                'clerking_section_id' => 3,
                'question' => 'If yes, what diagnosis or treatment were you given?',
                'field_key' => 'care_previous_diagnosis',
                'input_type' => 'text',
                'depends_on_section_question_id' => $healthcareVisitId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($healthcareFollowUps as $question) {
            SectionQuestion::create($question);
        }

        $investigationsQuestion = SectionQuestion::create([
            'clerking_section_id' => 3,
            'question' => 'Have any investigations been done (e.g. blood test, scan)?',
            'field_key' => 'care_investigations_done',
            'input_type' => 'boolean',
        ]);

        SectionQuestion::create([
            'clerking_section_id' => 3,
            'question' => 'If yes, what were the results?',
            'field_key' => 'care_investigation_results',
            'input_type' => 'text',
            'depends_on_section_question_id' => $investigationsQuestion->id,
            'depends_on_answer' => 'yes',
        ]);

        // Long-term medication questions (during current admission)
        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $longTermMedicationId = $latest->id;

        $longTermMedicationQuestions = [
            [
                'clerking_section_id' => 3,
                'question' => 'Is the patient currently on any medications since admission to this hospital?',
                'field_key' => 'long_term_medications',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What are those medications?',
                'field_key' => 'long_term_medication_names',
                'input_type' => 'text',
                'depends_on_section_question_id' => $longTermMedicationId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What is the dosage and frequency of intake of the medication?',
                'field_key' => 'long_term_medication_details',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $longTermMedicationId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($longTermMedicationQuestions as $question) {
            SectionQuestion::create($question);
        }
    }
}
