<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class PreNatalHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $plannedId = $sectionQuestion->id + 1;

        session([
            'begin_prenatal_id' => $plannedId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 5,
                'question' => 'Was the pregnancy planned?',
                'field_key' => 'pregnancy_planned',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'What trimester did the mother book for antenatal care?',
                'field_key' => 'anc_booking_trimester',
                'input_type' => 'select',
                'options' => [
                    'first' => 'First Trimester',
                    'second' => 'Second Trimester',
                    'third' => 'Third Trimester',
                    'never' => 'Never Booked',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'How many antenatal visits did the mother attend?',
                'field_key' => 'anc_visit_count',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    '1-3' => '1 - 3 visits',
                    '4-7' => '4 - 7 visits',
                    '8_plus' => '8 or more visits',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother receive tetanus toxoid immunization during pregnancy?',
                'field_key' => 'tetanus_toxoid',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'How many doses of tetanus toxoid were given?',
                'field_key' => 'tetanus_toxoid_doses',
                'input_type' => 'number',
                'max_char' => 5,
                'depends_on_section_question_id' => $plannedId + 3,
                'depends_on_answer' => 'yes',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother receive iron and folic acid supplementation during pregnancy?',
                'field_key' => 'iron_folate_supplementation',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother receive malaria prophylaxis during pregnancy?',
                'field_key' => 'malaria_prophylaxis',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'What was the mother\'s blood group and genotype?',
                'field_key' => 'maternal_blood_group_genotype',
                'input_type' => 'text',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Was the mother tested for HIV during pregnancy?',
                'field_key' => 'hiv_tested',
                'input_type' => 'select',
                'options' => [
                    'yes_positive' => 'Yes — Positive',
                    'yes_negative' => 'Yes — Negative',
                    'not_tested' => 'Not Tested',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother receive PMTCT (Prevention of Mother-to-Child Transmission) treatment?',
                'field_key' => 'pmtct_received',
                'input_type' => 'boolean',
                'depends_on_section_question_id' => $plannedId + 8,
                'depends_on_answer' => 'yes',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother have any illnesses during pregnancy?',
                'field_key' => 'maternal_illnesses',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'malaria' => 'Malaria',
                    'uti' => 'Urinary Tract Infection',
                    'hypertension' => 'Hypertension / Pre-eclampsia',
                    'diabetes' => 'Gestational Diabetes',
                    'anaemia' => 'Anaemia',
                    'infections' => 'Other Infections',
                    'multiple' => 'Multiple Conditions',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Was there any bleeding during pregnancy?',
                'field_key' => 'antenatal_bleeding',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'first_trimester' => 'First Trimester',
                    'second_trimester' => 'Second Trimester',
                    'third_trimester' => 'Third Trimester',
                    'multiple' => 'Multiple Episodes',
                ],
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Was the mother hospitalized during pregnancy?',
                'field_key' => 'antenatal_hospitalization',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother have any abdominal trauma or falls during pregnancy?',
                'field_key' => 'abdominal_trauma',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'Did the mother use any traditional or herbal medications during pregnancy?',
                'field_key' => 'traditional_medication_use',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 5,
                'question' => 'How many ultrasound scans were done during the pregnancy?',
                'field_key' => 'ultrasound_count',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'one' => 'One',
                    'two' => 'Two',
                    'three_plus' => 'Three or more',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_prenatal_id' => $latest->id,
        ]);
    }
}
