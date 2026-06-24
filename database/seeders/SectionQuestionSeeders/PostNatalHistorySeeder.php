<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class PostNatalHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_postnatal_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 7,
                'question' => "What was the baby's condition immediately after birth?",
                'field_key' => 'immediate_postnatal_condition',
                'input_type' => 'select',
                'options' => [
                    'good' => 'Good — Cried well, pink, active',
                    'fair' => 'Fair — Some stimulation needed',
                    'poor' => 'Poor — Required resuscitation',
                    'critical' => 'Critical — NICU/SCBU admission',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Was the baby admitted to the NICU or SCBU?',
                'field_key' => 'nicu_admission',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'If admitted, how long was the stay (days)?',
                'field_key' => 'nicu_stay_duration',
                'input_type' => 'number',
                'max_char' => 5,
                'depends_on_section_question_id' => $firstId + 1,
                'depends_on_answer' => 'yes',
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'How long did the mother and baby stay in hospital after delivery?',
                'field_key' => 'postnatal_hospital_stay',
                'input_type' => 'select',
                'options' => [
                    'less_24h' => 'Less than 24 hours',
                    '1_3_days' => '1 - 3 days',
                    '4_7_days' => '4 - 7 days',
                    'more_7_days' => 'More than 7 days',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Did the baby pass meconium within the first 24 hours?',
                'field_key' => 'meconium_passage',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Did the baby pass urine within the first 24 hours?',
                'field_key' => 'urine_passage',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'When was the baby first put to the breast?',
                'field_key' => 'first_breastfeeding',
                'input_type' => 'select',
                'options' => [
                    'within_1h' => 'Within 1 hour',
                    '1_24h' => '1 - 24 hours',
                    'after_24h' => 'After 24 hours',
                    'never' => 'Never breastfed',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby had any difficulty feeding?',
                'field_key' => 'feeding_difficulties',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'poor_suck' => 'Poor suck',
                    'vomiting' => 'Vomiting / Regurgitation',
                    'refusal' => 'Refuses to feed',
                    'choking' => 'Choking / Coughing during feeds',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby been jaundiced (yellow skin or eyes)?',
                'field_key' => 'neonatal_jaundice',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'yes_mild' => 'Yes — Mild (self-resolved)',
                    'yes_phototherapy' => 'Yes — Required phototherapy',
                    'yes_exchange' => 'Yes — Required exchange transfusion',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby had any difficulty breathing?',
                'field_key' => 'neonatal_respiratory_distress',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'mild' => 'Mild — Self-resolved',
                    'oxygen' => 'Required oxygen',
                    'ventilation' => 'Required ventilation',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby had any abnormal movements or seizures?',
                'field_key' => 'neonatal_seizures',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has there been any umbilical cord redness, discharge, or infection?',
                'field_key' => 'cord_infection',
                'input_type' => 'select',
                'options' => [
                    'no' => 'No',
                    'mild_redness' => 'Mild redness',
                    'discharge' => 'Discharge / Pus',
                    'bleeding' => 'Bleeding from cord',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby had any skin rashes or infections?',
                'field_key' => 'neonatal_skin_issues',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'nappy_rash' => 'Nappy Rash',
                    'miliaria' => 'Miliaria (Heat rash)',
                    'pustules' => 'Pustules / Bullae',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Has the baby had any eye discharge or redness?',
                'field_key' => 'neonatal_eye_infection',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 7,
                'question' => 'Was the baby screened for any medical conditions before discharge?',
                'field_key' => 'newborn_screening',
                'input_type' => 'select',
                'options' => [
                    'none' => 'No screening done',
                    'hearing' => 'Hearing screening',
                    'blood_spot' => 'Blood spot (G6PD, sickle cell, thyroid)',
                    'both' => 'Hearing and blood spot',
                    'unknown' => 'Unknown',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_postnatal_id' => $latest->id,
        ]);
    }
}
