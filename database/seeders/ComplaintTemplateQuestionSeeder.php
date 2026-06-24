<?php

namespace Database\Seeders;

use App\Models\ComplaintTemplate;
use Illuminate\Database\Seeder;

class ComplaintTemplateQuestionSeeder extends Seeder {
    public function run(): void {
        $feverComplaint = ComplaintTemplate::where('slug', 'fever')->first();

        $feverComplaint->questions()->createMany([
            [
                'clerking_section_id' => 3,
                'question' => 'How long have you had the fever?',
                'field_key' => 'duration_of_fever',
                'input_type' => 'text',
                'order' => 1,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'How high was the fever?',
                'field_key' => 'fever_grade',
                'input_type' => 'select',
                'options' => [
                    'low-grade' => 'Low grade',
                    'high-grade' => 'High grade',
                ],
                'order' => 2,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Was the onset gradual or sudden?',
                'field_key' => 'onset_of_fever',
                'input_type' => 'select',
                'options' => [
                    'gradual' => 'Gradual',
                    'sudden' => 'Sudden',
                ],
                'order' => 3,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What is the pattern of the fever? Any time of the day it gets better or worse?',
                'field_key' => 'fever_pattern',
                'input_type' => 'text',
                'order' => 4,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated chills or rigors?',
                'field_key' => 'fever_chills_rigors',
                'input_type' => 'boolean',
                'order' => 5,
            ],

            [
                'clerking_section_id' => 3,
                'question' => "What is the fever's response to drugs (eg. Paracetamol, Chloroquine, Quinine, etc)",
                'field_key' => 'fever_drug_response',
                'input_type' => 'select',
                'options' => [
                    'responsive' => 'Responsive',
                    'unresponsive' => 'Unresponsive',
                    'partially_responsive' => 'Partially responsive',
                ],
                'order' => 6,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have headache?',
                'field_key' => 'fever_headache',
                'input_type' => 'boolean',
                'order' => 7,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any neck stiffness or sensitivity to light?',
                'field_key' => 'fever_meningeal_symptoms',
                'input_type' => 'boolean',
                'order' => 8,
            ],
        ]);

        $coughQuestion = $feverComplaint->questions()->create([
            'clerking_section_id' => 3,
            'question' => 'Do you have cough?',
            'field_key' => 'fever_cough',
            'input_type' => 'boolean',
            'order' => 9,
        ]);

        $feverComplaint->questions()->createMany([
            [
                'clerking_section_id' => 3,
                'question' => 'Are you producing sputum?',
                'field_key' => 'fever_sputum',
                'input_type' => 'boolean',
                'order' => 10,
                'depends_on_complaint_question_id' => $coughQuestion->id,
                'depends_on_answer' => 'yes',
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any chest pain or difficulty breathing?',
                'field_key' => 'fever_chest_symptoms',
                'input_type' => 'boolean',

                'order' => 11,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any diarrhea?',
                'field_key' => 'fever_diarrhea',

                'input_type' => 'boolean',
                'order' => 12,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any vomiting?',
                'field_key' => 'fever_vomiting',

                'input_type' => 'boolean',
                'order' => 13,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any joint or muscle pains?',
                'field_key' => 'fever_joint_pain',
                'input_type' => 'boolean',
                'order' => 14,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Have you had recent travel?',
                'field_key' => 'fever_travel_history',
                'input_type' => 'boolean',
                'order' => 15,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any recent consumption of unsafe food or water?',
                'field_key' => 'fever_food_water_exposure',
                'input_type' => 'boolean',
                'order' => 16,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Have you experienced weight loss or night sweats?',
                'field_key' => 'fever_weight_loss_night_sweats',
                'input_type' => 'boolean',
                'order' => 17,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any seizure?',
                'field_key' => 'fever_seizure',

                'input_type' => 'boolean',
                'order' => 18,
            ],
        ]);

        $chestPainComplaint = ComplaintTemplate::where('slug', 'chest-pain')->first();

        $chestPainComplaint->questions()->createMany([
            // SOCRATES
            [
                'clerking_section_id' => 3,
                'question' => 'Where exactly in the chest do you feel the pain?',
                'field_key' => 'chest_pain_location',
                'input_type' => 'text',
                'order' => 1,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Was it gradual or sudden in onset?',
                'field_key' => 'chest_pain_onset',
                'input_type' => 'select',
                'options' => [
                    'gradual' => 'Gradual',
                    'sudden' => 'Sudden',
                ],
                'order' => 2,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What is the character of the pain?',
                'field_key' => 'chest_pain_character',
                'input_type' => 'select',
                'options' => [
                    'tightness' => 'Tightness',
                    'pressure' => 'Pressure',
                    'burning' => 'Burning',
                    'sharp' => 'Sharp',
                    'crushing' => 'Crushing',
                    'stabbing' => 'Stabbing',
                    'other' => 'Other',
                ],
                'order' => 3,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated shortness of breath?',
                'field_key' => 'chest_pain_associated_shortness_of_breath',

                'input_type' => 'boolean',
                'order' => 4,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated nausea?',
                'field_key' => 'chest_pain_associated_nausea',

                'input_type' => 'boolean',
                'order' => 5,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated vomiting?',
                'field_key' => 'chest_pain_associated_vomiting',

                'input_type' => 'boolean',
                'order' => 6,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated sweating?',
                'field_key' => 'chest_pain_associated_sweating',
                'input_type' => 'boolean',
                'order' => 7,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated dizziness?',
                'field_key' => 'chest_pain_associated_dizziness',
                'input_type' => 'boolean',
                'order' => 8,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated fainting?',
                'field_key' => 'chest_pain_associated_fainting',

                'input_type' => 'boolean',
                'order' => 9,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated palpitations?',
                'field_key' => 'chest_pain_associated_palpitations',
                'input_type' => 'boolean',
                'order' => 10,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated cough?',
                'field_key' => 'chest_pain_associated_cough',
                'input_type' => 'boolean',
                'order' => 11,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any associated sputum production?',
                'field_key' => 'chest_pain_associated_sputum',
                'input_type' => 'boolean',
                'order' => 12,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'If there is a productive cough, what is the color of the sputum?',
                'field_key' => 'chest_pain_associated_sputum_color',
                'options' => [
                    'clear' => 'Clear',
                    'white' => 'White',
                    'yellow' => 'Yellow',
                    'green' => 'Green',
                    'blood_streaked' => 'Blood streaked',
                    'none' => 'No sputum production',
                ],
                'input_type' => 'select',
                'order' => 13,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Any pain radiation?',
                'field_key' => 'chest_pain_radiation',
                'input_type' => 'text',
                'order' => 14,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Is there any time of the day it gets objectively better or worse?',
                'field_key' => 'chest_pain_time_factor',
                'input_type' => 'text',
                'order' => 15,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What makes the pain worse?',
                'field_key' => 'chest_pain_worse',
                'input_type' => 'text',
                'order' => 16,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'What makes the pain better?',
                'field_key' => 'chest_pain_better',
                'input_type' => 'text',
                'order' => 17,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'How severe is the chest pain on a scale of 1 to 10?',
                'field_key' => 'chest_pain_severity',
                'input_type' => 'scale',
                'order' => 18,
            ],
        ]);

        $sobComplaint = ComplaintTemplate::where('slug', 'shortness-of-breath')->first();
        $order = 1;
        $sobComplaint->questions()->createMany([
            [
                'clerking_section_id' => 3,
                'question' => 'Did the shortness of breath start suddenly?',
                'field_key' => 'sob_sudden_onset',
                'input_type' => 'boolean',
                'order' => $order++,
            ],
            [
                'clerking_section_id' => 3,
                'question' => 'Do you experience breathlessness when lying flat?',
                'field_key' => 'sob_orthopnea',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you wake up at night suddenly short of breath?',
                'field_key' => 'sob_pnd',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have leg swelling?',
                'field_key' => 'sob_leg_swelling',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have chest pain?',
                'field_key' => 'sob_chest_pain',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have cough?',
                'field_key' => 'sob_cough',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Are you producing sputum?',
                'field_key' => 'sob_sputum',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have wheezing?',
                'field_key' => 'sob_wheeze',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have fever?',
                'field_key' => 'sob_fever',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you cough up blood?',
                'field_key' => 'sob_hemoptysis',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you feel unusually tired or weak?',
                'field_key' => 'sob_fatigue',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Have you noticed palpitations?',
                'field_key' => 'sob_palpitations',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you feel dizzy or lightheaded?',
                'field_key' => 'sob_dizziness',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Do you have any tingling in your hands or around your mouth?',
                'field_key' => 'sob_tingling',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 3,
                'question' => 'Is your breathing deep and rapid?',
                'field_key' => 'sob_kussmaul_breathing',
                'input_type' => 'boolean',
                'order' => $order++,
            ],
        ]);

        $feverInaChildComplaint = ComplaintTemplate::where('slug', 'fever-in-a-child')->first();
        $order = 1;

        $feverInaChildComplaint->questions()->createMany([
            [
                'clerking_section_id' => 4,
                'question' => 'How long has the child had a fever?',
                'field_key' => 'fever_child_duration',
                'input_type' => 'text',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'What is the highest temperature recorded?',
                'field_key' => 'fever_child_highest_temp',
                'input_type' => 'text',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child been restless, irritable or crying more than usual?',
                'field_key' => 'fever_child_restless',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child been lethargic or unusually sleepy?',
                'field_key' => 'fever_child_lethargic',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child been breathing faster than usual?',
                'field_key' => 'fever_child_rapid_breathing',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Is the child feeding poorly?',
                'field_key' => 'fever_child_poor_feeding',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child been vomiting?',
                'field_key' => 'fever_child_vomiting',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child had diarrhea?',
                'field_key' => 'fever_child_diarrhea',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Does the child have a rash?',
                'field_key' => 'fever_child_rash',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child had any seizures?',
                'field_key' => 'fever_child_seizures',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child had any recent travel?',
                'field_key' => 'fever_child_recent_travel',
                'input_type' => 'boolean',
                'order' => $order++,
            ],

            [
                'clerking_section_id' => 4,
                'question' => 'Has the child had any contact with sick individuals?',
                'field_key' => 'fever_child_sick_contact',
                'input_type' => 'boolean',
                'order' => $order++,
            ],
        ]);
    }
}
