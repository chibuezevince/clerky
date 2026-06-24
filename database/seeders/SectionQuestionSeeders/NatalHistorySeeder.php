<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class NatalHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_natal_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 6,
                'question' => 'Where was the baby delivered?',
                'field_key' => 'place_of_delivery',
                'input_type' => 'select',
                'options' => [
                    'hospital' => 'Hospital',
                    'health_centre' => 'Health Centre',
                    'home' => 'Home',
                    'tba' => 'Traditional Birth Attendant',
                    'en_route' => 'En Route to Facility',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'What was the mode of delivery?',
                'field_key' => 'mode_of_delivery',
                'input_type' => 'select',
                'options' => [
                    'svd' => 'Spontaneous Vaginal Delivery (SVD)',
                    'assisted_vaginal' => 'Assisted Vaginal Delivery (Vacuum / Forceps)',
                    'elective_cs' => 'Elective Caesarean Section',
                    'emergency_cs' => 'Emergency Caesarean Section',
                    'breech' => 'Breech Delivery',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Who conducted the delivery?',
                'field_key' => 'delivery_conducted_by',
                'input_type' => 'select',
                'options' => [
                    'doctor' => 'Doctor',
                    'midwife' => 'Midwife / Nurse',
                    'tba' => 'Traditional Birth Attendant',
                    'relative' => 'Relative',
                    'self' => 'Delivered on own',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was the baby born at term?',
                'field_key' => 'gestational_age_at_birth',
                'input_type' => 'select',
                'options' => [
                    'term' => 'Term (37-42 weeks)',
                    'preterm' => 'Preterm (< 37 weeks)',
                    'post_term' => 'Post-term (> 42 weeks)',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'What was the duration of labour?',
                'field_key' => 'labour_duration',
                'input_type' => 'select',
                'options' => [
                    'normal' => 'Normal',
                    'prolonged' => 'Prolonged',
                    'precipitous' => 'Precipitous (< 3 hours)',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was this a singleton or multiple birth?',
                'field_key' => 'birth_type',
                'input_type' => 'select',
                'options' => [
                    'singleton' => 'Singleton',
                    'twins' => 'Twins',
                    'triplets' => 'Triplets',
                    'higher' => 'Higher Order Multiple',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'What was the baby\'s presentation at birth?',
                'field_key' => 'fetal_presentation',
                'input_type' => 'select',
                'options' => [
                    'cephalic' => 'Cephalic (Head first)',
                    'breech' => 'Breech',
                    'transverse' => 'Transverse Lie',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was there meconium staining of the liquor?',
                'field_key' => 'meconium_staining',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'mild' => 'Mild',
                    'moderate' => 'Moderate',
                    'severe' => 'Severe / Thick',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'What was the baby\'s birth weight (kg)?',
                'field_key' => 'birth_weight',
                'input_type' => 'number',
                'max_char' => 10,
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Did the baby cry immediately after birth?',
                'field_key' => 'cried_immediately',
                'input_type' => 'select',
                'options' => [
                    'yes_immediately' => 'Yes — Cried immediately',
                    'yes_delayed' => 'Yes — After stimulation',
                    'no_resuscitation' => 'No — Required resuscitation',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was resuscitation required at birth?',
                'field_key' => 'resuscitation_required',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'oxygen' => 'Oxygen only',
                    'bag_mask' => 'Bag and Mask Ventilation',
                    'intubation' => 'Intubation',
                    'chest_compressions' => 'Full CPR',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Were there any complications during delivery?',
                'field_key' => 'delivery_complications',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'cord_prolapse' => 'Cord Prolapse',
                    'shoulder_dystocia' => 'Shoulder Dystocia',
                    'postpartum_haemorrhage' => 'Postpartum Haemorrhage',
                    'eclampsia' => 'Eclampsia / Seizures',
                    'ruptured_uterus' => 'Ruptured Uterus',
                    'birth_asphyxia' => 'Birth Asphyxia',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Were there any birth injuries?',
                'field_key' => 'birth_injuries',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'cephalohematoma' => 'Cephalohematoma',
                    'caput_succedaneum' => 'Caput Succedaneum',
                    'brachial_plexus' => 'Brachial Plexus Injury',
                    'clavicle_fracture' => 'Clavicle Fracture',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was the cord cut and cleaned properly?',
                'field_key' => 'cord_care',
                'input_type' => 'select',
                'options' => [
                    'yes_clean' => 'Yes — Clean instrument',
                    'yes_unclean' => 'Yes — But unsterile instrument',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 6,
                'question' => 'Was the baby given any medication immediately after birth?',
                'field_key' => 'postnatal_medications',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'vitamin_k' => 'Vitamin K',
                    'eye_drops' => 'Eye Drops / Ointment',
                    'both' => 'Vitamin K and Eye Drops',
                    'unknown' => 'Unknown',
                ],
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_natal_id' => $latest->id,
        ]);
    }
}
