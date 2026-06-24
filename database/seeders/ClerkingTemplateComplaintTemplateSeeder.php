<?php

namespace Database\Seeders;

use App\Models\ClerkingTemplate;
use App\Models\ComplaintTemplate;
use Illuminate\Database\Seeder;

class ClerkingTemplateComplaintTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $feverComplaint = ComplaintTemplate::where('slug', 'fever')->first();
        $feverComplaint->clerkingTemplates()->attach([
            ClerkingTemplate::where('slug', 'gen-med-surg')->first()->id,
            ClerkingTemplate::where('slug', 'obs-gyn')->first()->id,
        ]);

        $shortnessOfBreathComplaint = ComplaintTemplate::where('slug', 'shortness-of-breath')->first();
        $shortnessOfBreathComplaint->clerkingTemplates()->attach([
            ClerkingTemplate::where('slug', 'gen-med-surg')->first()->id,
            ClerkingTemplate::where('slug', 'obs-gyn')->first()->id,
        ]);

        $chestPainComplaint = ComplaintTemplate::where('slug', 'chest-pain')->first();
        $chestPainComplaint->clerkingTemplates()->attach([
            ClerkingTemplate::where('slug', 'gen-med-surg')->first()->id,
            ClerkingTemplate::where('slug', 'obs-gyn')->first()->id,
        ]);

        $feverInChildComplaint = ComplaintTemplate::where('slug', 'fever-in-a-child')->first();
        $feverInChildComplaint->clerkingTemplates()->attach([
            ClerkingTemplate::where('slug', 'paediatrics')->first()->id,
        ]);
    }
}
