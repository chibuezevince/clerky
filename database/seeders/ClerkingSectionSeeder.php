<?php

namespace Database\Seeders;

use App\Models\ClerkingSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClerkingSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'Biodata' => 'The patient\'s personal information including name, age, gender, and contact details',
            'Presenting Complaint' => 'The main reason the patient is seeking medical attention',
            'History of Presenting Complaint' => 'A detailed account of the symptoms and events leading to the consultation',
            'Past Medical and Surgical History' => 'Previous medical conditions, illnesses, and operations',
            'Pre-natal History' => 'Detailed information surrounding the pregnancy',
            'Natal History' => 'Detailed information surrounding the child-birth',
            'Post-Natal History' => 'Detailed information surrounding the neonatal period',
            'Nutritional History' => 'Current and past nutritional information, including diet and supplementation',
            'Developmental History' => 'Detailed information about the patient\'s developmental milestones, including crawling, walking, and speech',
            'Immunisation History' => 'Detailed information about the patient\'s immunisation history, including vaccines and boosters. This will contain when the first vaccine was given, the presence or absence of a BCG scar or any visible immunisation marks or a presented immunisation card.',
            'Drug History' => 'Current and past medications, including dosage and frequency',
            'Obstetric History' => 'This section captures details of any past pregnancies, including their number, outcomes, and any related complications, to help assess reproductive history and risks in future or current pregnancies.',
            'Menstrual History' => 'This section collects details about the patient’s menstrual cycle, including the age at first menstruation, cycle regularity, duration, flow, and any associated symptoms, to help assess reproductive and hormonal health.',
            'Contraceptive History' => 'This section records any past or current use of contraceptive methods, including the type, duration of use, effectiveness, and any side effects or complications experienced, to assess fertility planning and reproductive health.',
            'Booking Details' => 'This section captures details of the patient’s antenatal booking, including when and where they first registered for pregnancy care, the gestational age at booking, and any initial assessments or findings at that time.',
            'Family and Social History' => 'Medical conditions of family members to identify genetic predispositions or lifestyle factors. This will include questions about the patient\'s occupation, housing, diet, and smoking status.',
            'Review of Systems' => 'A systematic review of different body systems to identify any additional symptoms',
            'General Examination' => 'Physical examination findings including vital signs and system-specific findings',
            'Systemic Examination' => 'Detailed findings from examination of the respiratory, cardiovascular, gastrointestinal, and neurological systems. ',
            'Anthropometry' => 'Details regarding various body measurements including weight, height, and head circumference. This will also include MUAC for paediatric patients and BMI for adult patients.',
        ];

        foreach ($sections as $name => $description) {
            ClerkingSection::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
                'complaint_question_position' => $name === 'History of Presenting Complaint' ? 'before' : 'after',
            ]);
        }
    }
}
