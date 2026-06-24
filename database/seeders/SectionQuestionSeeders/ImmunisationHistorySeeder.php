<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class ImmunisationHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_immunisation_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 10,
                'question' => 'Is there a vaccination card available?',
                'field_key' => 'vaccination_card_available',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the BCG vaccine (usually given at birth, leaves a scar)?',
                'field_key' => 'bcg_vaccine',
                'input_type' => 'select',
                'options' => [
                    'yes_card' => 'Yes — Confirmed by card',
                    'yes_scar' => 'Yes — BCG scar visible',
                    'yes_both' => 'Yes — Card and scar present',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the Hepatitis B vaccine (birth dose)?',
                'field_key' => 'hepatitis_b_birth_dose',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the Oral Polio Vaccine (OPV) at birth (zero dose)?',
                'field_key' => 'opv_birth_dose',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the first dose of Pentavalent (DPT-HepB-Hib) vaccine?',
                'field_key' => 'penta_1',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the second dose of Pentavalent vaccine?',
                'field_key' => 'penta_2',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the third dose of Pentavalent vaccine?',
                'field_key' => 'penta_3',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the first dose of Inactivated Polio Vaccine (IPV)?',
                'field_key' => 'ipv_1',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the second dose of Inactivated Polio Vaccine (IPV)?',
                'field_key' => 'ipv_2',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the PCV (Pneumococcal) vaccine?',
                'field_key' => 'pcv_vaccine',
                'input_type' => 'select',
                'options' => [
                    'all_doses' => 'Yes — All doses completed',
                    'partial' => 'Yes — Partial doses',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the Rotavirus vaccine?',
                'field_key' => 'rotavirus_vaccine',
                'input_type' => 'select',
                'options' => [
                    'all_doses' => 'Yes — All doses completed',
                    'partial' => 'Yes — Partial doses',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the Measles-Rubella (MR) or Measles vaccine?',
                'field_key' => 'measles_vaccine',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Has the child received the Yellow Fever vaccine?',
                'field_key' => 'yellow_fever_vaccine',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'too_young' => 'Too young (given at 9 months)',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 10,
                'question' => 'Are all vaccinations up to date according to the national schedule?',
                'field_key' => 'vaccination_uptodate',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes — Up to date',
                    'no_missed' => 'No — Missed some doses',
                    'no_started' => 'No — Not yet started',
                    'unknown' => 'Unknown',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_immunisation_id' => $latest->id,
        ]);
    }
}
