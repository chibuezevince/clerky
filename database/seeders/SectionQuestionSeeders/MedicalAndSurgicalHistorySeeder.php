<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class MedicalAndSurgicalHistorySeeder extends Seeder
{
    public function run(): void
    {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $pastAdmissionId = $sectionQuestion->id + 1;

        session([
            'begin_pmh_id' => $pastAdmissionId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Has the patient ever been admitted to a hospital before?',
                'field_key' => 'past_admissions',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What was the patient admitted for?',
                'field_key' => 'admission_reason',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $pastAdmissionId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'How long was the patient admitted for?',
                'field_key' => 'admission_duration',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $pastAdmissionId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Was there any drug given during admission?',
                'field_key' => 'drug_given_during_admission',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $pastAdmissionId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Was there any investigations done?',
                'field_key' => 'investigations_done_during_admission',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $pastAdmissionId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
                'question' => "Why were they referred to the hospital you're currently in?",
                'field_key' => 'referral_reason',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $pastAdmissionId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $bloodTransfusionId = $latest->id + 1;

        $bloodTransfusionQuestions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Any history of blood transfusion?',
                'field_key' => 'blood_transfusion',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What is the quantity of blood that was transfused?',
                'field_key' => 'blood_transfusion_quantity',
                'input_type' => 'text',
                'depends_on_section_question_id' => $bloodTransfusionId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Why was the blood transfused?',
                'field_key' => 'blood_transfusion_reason',
                'input_type' => 'text',
                'depends_on_section_question_id' => $bloodTransfusionId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($bloodTransfusionQuestions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $pastSurgeriesId = $latest->id + 1;

        $surgeryQuestions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Has the patient undergone any surgeries in the past?',
                'field_key' => 'past_surgeries',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What was the reason for the surgery? Name of the procedure?',
                'field_key' => 'past_surgery_procedure',
                'input_type' => 'text',
                'depends_on_section_question_id' => $pastSurgeriesId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($surgeryQuestions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $longTermMedicationId = $latest->id + 1;

        $longTermMedicationQuestions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Is the patient currently on any long-term medications before admission?',
                'field_key' => 'long_term_medications',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What are those medications?',
                'field_key' => 'long_term_medication_names',
                'input_type' => 'text',
                'depends_on_section_question_id' => $longTermMedicationId,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 4,
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

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $drugAllergiesId = $latest->id + 1;

        $drugAllergiesQuestions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Any known drug allergies or reactions?',
                'field_key' => 'drug_allergies',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'List those allergies',
                'field_key' => 'drug_allergies_list',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $drugAllergiesId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($drugAllergiesQuestions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        $chronicQuestionId = $latest->id + 1;

        $chronicQuestions = [
            [
                'clerking_section_id' => 4,
                'question' => 'Does the patient have any known chronic medical conditions (e.g. hypertension, diabetes, asthma)?',
                'field_key' => 'chronic_conditions',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What are those chronic conditions',
                'field_key' => 'chronic_conditions_list',
                'input_type' => 'textarea',
                'depends_on_section_question_id' => $chronicQuestionId,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($chronicQuestions as $question) {
            SectionQuestion::create($question);
        }

        $latest->refresh();

        session([
            'end_pmh_id' => $latest->id,
        ]);
    }
}
