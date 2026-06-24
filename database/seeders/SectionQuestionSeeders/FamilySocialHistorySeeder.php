<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class FamilySocialHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_fsh_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 16,
                'question' => 'Are the child\'s parents related by blood (consanguinity)?',
                'field_key' => 'consanguinity',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'first_cousins' => 'Yes — First cousins',
                    'second_cousins' => 'Yes — Second cousins',
                    'other' => 'Yes — Other relation',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of sickle cell disease or trait?',
                'field_key' => 'family_history_sickle_cell',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'sickle_cell_disease' => 'Yes — Sickle cell disease',
                    'sickle_cell_trait' => 'Yes — Sickle cell trait',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of asthma or allergies?',
                'field_key' => 'family_history_asthma',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'yes' => 'Yes',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of diabetes mellitus?',
                'field_key' => 'family_history_diabetes',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of hypertension or heart disease?',
                'field_key' => 'family_history_hypertension',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of epilepsy or seizures?',
                'field_key' => 'family_history_epilepsy',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of mental health conditions?',
                'field_key' => 'family_history_mental_health',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Is there any family history of tuberculosis or chronic cough?',
                'field_key' => 'family_history_tb',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'How many siblings does the child have?',
                'field_key' => 'number_of_siblings',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None (only child)',
                    'one' => 'One',
                    'two' => 'Two',
                    'three' => 'Three',
                    'four_plus' => 'Four or more',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Are all siblings alive and well?',
                'field_key' => 'siblings_health',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes — All alive and well',
                    'some_deceased' => 'Some deceased',
                    'some_ill' => 'Some with medical conditions',
                    'not_applicable' => 'Not applicable',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'Does anyone in the household smoke tobacco?',
                'field_key' => 'household_smoking',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'yes_indoors' => 'Yes — Smokes indoors',
                    'yes_outdoors' => 'Yes — Smokes outdoors only',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'What type of housing does the family live in?',
                'field_key' => 'housing_type',
                'input_type' => 'select',
                'options' => [
                    'self_contained' => 'Self-contained',
                    'shared' => 'Shared / Compound house',
                    'single_room' => 'Single room',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'What is the main source of drinking water for the household?',
                'field_key' => 'water_source',
                'input_type' => 'select',
                'options' => [
                    'piped' => 'Piped / Tap water',
                    'borehole' => 'Borehole / Well',
                    'sachet' => 'Sachet / Bottled water',
                    'river' => 'River / Stream',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 16,
                'question' => 'What type of toilet facility does the household use?',
                'field_key' => 'toilet_facility',
                'input_type' => 'select',
                'options' => [
                    'flush' => 'Flush toilet',
                    'pit' => 'Pit latrine',
                    'ventilated' => 'Ventilated improved pit (VIP)',
                    'open' => 'Open defecation',
                    'other' => 'Other',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_fsh_id' => $latest->id,
        ]);
    }
}