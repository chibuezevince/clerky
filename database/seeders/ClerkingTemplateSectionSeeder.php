<?php

namespace Database\Seeders;

use App\Models\ClerkingSection;
use App\Models\ClerkingTemplate;
use Illuminate\Database\Seeder;
use Str;

class ClerkingTemplateSectionSeeder extends Seeder {
    public function run(): void {
        $biodata = ClerkingSection::where('slug', Str::slug('Biodata'))->first();
        $pc = ClerkingSection::where('slug', Str::slug('Presenting Complaint'))->first();
        $hpc = ClerkingSection::where('slug', Str::slug('History of Presenting Complaint'))->first();
        $pmh = ClerkingSection::where('slug', Str::slug('Past Medical and Surgical History'))->first();
        $drugs = ClerkingSection::where('slug', Str::slug('Drug History'))->first();
        $family = ClerkingSection::where('slug', Str::slug('Family and Social History'))->first();
        $ros = ClerkingSection::where('slug', Str::slug('Review of Systems'))->first();
        $generalExam = ClerkingSection::where('slug', Str::slug('General Examination'))->first();
        $systemicExam = ClerkingSection::where('slug', Str::slug('Systemic Examination'))->first();

        // paeds-specific
        $antenatal = ClerkingSection::where('slug', Str::slug('Pre-natal History'))->first();
        $natal = ClerkingSection::where('slug', Str::slug('Natal History'))->first();
        $postnatal = ClerkingSection::where('slug', Str::slug('Post-natal History'))->first();
        $developmental = ClerkingSection::where('slug', Str::slug('Developmental History'))->first();
        $nutritional = ClerkingSection::where('slug', Str::slug('Nutritional History'))->first();
        $immunisation = ClerkingSection::where('slug', Str::slug('Immunisation History'))->first();
        $paedAnthropometry = ClerkingSection::where('slug', Str::slug('Anthropometry'))->first();

        // o&g-specific
        $obstetric = ClerkingSection::where('slug', Str::slug('Obstetric History'))->first();
        $menstrual = ClerkingSection::where('slug', Str::slug('Menstrual History'))->first();
        $contraceptive = ClerkingSection::where('slug', Str::slug('Contraceptive History'))->first();
        $booking = ClerkingSection::where('slug', Str::slug('Booking Details'))->first();
        $ogAnthropometry = ClerkingSection::where('slug', Str::slug('Anthropometry'))->first();

        $genMed = ClerkingTemplate::where('slug', 'gen-med-surg')->first();
        $paeds = ClerkingTemplate::where('slug', 'paediatrics')->first();
        $obsGyn = ClerkingTemplate::where('slug', 'obs-gyn')->first();

        $genMed->sections()->attach([
            $biodata->id => ['order' => 1],
            $pc->id => ['order' => 2],
            $hpc->id => ['order' => 3],
            $pmh->id => ['order' => 4],
            $drugs->id => ['order' => 5],
            $family->id => ['order' => 6],
            $ros->id => ['order' => 7],
            $generalExam->id => ['order' => 8],
            $systemicExam->id => ['order' => 9],
        ]);

        $paeds->sections()->attach([
            $biodata->id => ['order' => 1],
            $pc->id => ['order' => 2],
            $hpc->id => ['order' => 3],
            $pmh->id => ['order' => 4],
            $antenatal->id => ['order' => 5],
            $natal->id => ['order' => 6],
            $postnatal->id => ['order' => 7],
            $developmental->id => ['order' => 8],
            $nutritional->id => ['order' => 9],
            $immunisation->id => ['order' => 10],
            $family->id => ['order' => 11],
            $ros->id => ['order' => 12],
            $generalExam->id => ['order' => 13],
            $systemicExam->id => ['order' => 14],
            $paedAnthropometry->id => ['order' => 15],
        ]);

        $obsGyn->sections()->attach([
            $biodata->id => ['order' => 1],
            $pc->id => ['order' => 2],
            $hpc->id => ['order' => 3],
            $obstetric->id => ['order' => 4],
            $menstrual->id => ['order' => 5],
            $contraceptive->id => ['order' => 6],
            $booking->id => ['order' => 7],
            $pmh->id => ['order' => 8],
            $drugs->id => ['order' => 9],
            $family->id => ['order' => 10],
            $ros->id => ['order' => 11],
            $generalExam->id => ['order' => 12],
            $systemicExam->id => ['order' => 13],
            $ogAnthropometry->id => ['order' => 14],
        ]);

    }
}
