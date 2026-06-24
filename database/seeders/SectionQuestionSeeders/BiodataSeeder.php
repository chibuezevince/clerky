<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class BiodataSeeder extends Seeder {
    public function run(): void {
        session([
            'begin_biodata_id' => 1,
        ]);

        $questions = [
            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's name?",
                'field_key' => 'name',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => 'How old is the patient?',
                'field_key' => 'age',
                'input_type' => 'number',
                'max_char' => 120,
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's sex?",
                'field_key' => 'sex',
                'input_type' => 'select',
                'options' => [
                    'male' => 'Male',
                    'female' => 'Female',
                ],
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's occupation?",
                'field_key' => 'occupation',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's marital status?",
                'field_key' => 'marital_status',
                'input_type' => 'select',
                'options' => [
                    'single' => 'Single',
                    'married' => 'Married',
                    'divorced' => 'Divorced',
                    'widowed' => 'Widowed',
                ],
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's address?",
                'field_key' => 'address',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => 'What is their place of origin?',
                'field_key' => 'address_origin',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's religion and denomination?",
                'field_key' => 'religion',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => "Who is the patient's informant?",
                'field_key' => 'informant',
                'input_type' => 'multi_select',
                'options' => [
                    'patient' => 'Patient',
                    'mother' => 'Mother',
                    'father' => 'Father',
                    'guardian' => 'Guardian',
                    'caregiver' => 'Caregiver',
                    'spouse' => 'Spouse',
                    'husband' => 'Husband',
                    'wife' => 'Wife',
                    'son' => 'Son',
                    'daughter' => 'Daughter',
                    'sibling' => 'Sibling',
                    'brother' => 'Brother',
                    'sister' => 'Sister',
                    'grandmother' => 'Grandmother',
                    'grandfather' => 'Grandfather',
                    'uncle' => 'Uncle',
                    'aunt' => 'Aunt',
                    'friend' => 'Friend',
                    'neighbour' => 'Neighbour',
                    'teacher' => 'Teacher',
                    'social_worker' => 'Social Worker',
                    'health_worker' => 'Health Worker',
                    'police' => 'Police',
                    'relative' => 'Relative',
                    'other' => 'Other',
                ],
            ],

            [
                'clerking_section_id' => 1,
                'question' => 'When did the patient present to the hospital?',
                'field_key' => 'date_of_presentation',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's hospital number?",
                'field_key' => 'hospital_number',
                'input_type' => 'text',
            ],

            [
                'clerking_section_id' => 1,
                'question' => 'Where are you clerking the patient? (Ward, Clinic or Other)',
                'field_key' => 'clerking_location',
                'input_type' => 'select',
                'options' => [
                    'ward' => 'Ward',
                    'clinic' => 'Clinic',
                    'other' => 'Other',
                ],
            ],

            [
                'clerking_section_id' => 1,
                'question' => 'Who does the patient live with? (Mother, Father, Grandparents, Caregiver, etc.)',
                'field_key' => 'lives_with',
                'input_type' => 'select',
                'options' => [
                    'parents' => 'Both parents',
                    'mother' => 'Mother',
                    'father' => 'Father',
                    'grandparents' => 'Grandparents',
                    'orphanage' => 'Orphanage',
                    'caregiver' => 'Caregiver',
                    'spouse' => 'Spouse',
                    'husband' => 'Husband',
                    'sibling' => 'Sibling',
                    'brother' => 'Brother',
                    'sister' => 'Sister',
                    'grandmother' => 'Grandmother',
                    'grandfather' => 'Grandfather',
                    'uncle' => 'Uncle',
                    'aunt' => 'Aunt',
                    'friend' => 'Friend',
                    'relative' => 'Relative',
                    'other' => 'Other',
                ],
            ],

            [
                'clerking_section_id' => 1,
                'question' => "What is the patient's tribe?",
                'field_key' => 'ethnicity',
                'input_type' => 'text',
            ],
            // 13 questions at this point

        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();
        session([
            'end_biodata_id' => $latest->id,
        ]);
    }
}
