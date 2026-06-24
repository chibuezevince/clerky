<?php

namespace Database\Seeders;

use Database\Seeders\SectionQuestionSeeders\AnthropometrySeeder;
use Database\Seeders\SectionQuestionSeeders\BiodataSeeder;
use Database\Seeders\SectionQuestionSeeders\FamilySocialHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\GeneralExaminationSeeder;
use Database\Seeders\SectionQuestionSeeders\HistoryOfPresentingComplaintSeeder;
use Database\Seeders\SectionQuestionSeeders\SystemicExaminationSeeder;
use Database\Seeders\SectionQuestionSeeders\DevelopmentalHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\ImmunisationHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\MedicalAndSurgicalHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\NutritionalHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\PresentingComplaintSeeder;
use Database\Seeders\SectionQuestionSeeders\NatalHistorySeeder;use Database\Seeders\SectionQuestionSeeders\ReviewOfSystemsSeeder;use Database\Seeders\SectionQuestionSeeders\PostNatalHistorySeeder;
use Database\Seeders\SectionQuestionSeeders\PreNatalHistorySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            SettingSeeder::class,
            InstitutionSeeder::class,
            ClerkingTemplateSeeder::class,
            UnitSeeder::class,
            AcademicLevelSeeder::class,
            ClerkingSectionSeeder::class,
            ClerkingTemplateSectionSeeder::class,

            ComplaintTemplateSeeder::class,
            ComplaintTemplateQuestionSeeder::class,
            ClerkingTemplateComplaintTemplateSeeder::class,

            BiodataSeeder::class,
            PresentingComplaintSeeder::class,
            HistoryOfPresentingComplaintSeeder::class,
            MedicalAndSurgicalHistorySeeder::class,
            PreNatalHistorySeeder::class,
            NatalHistorySeeder::class,
            PostNatalHistorySeeder::class,
            DevelopmentalHistorySeeder::class,
            NutritionalHistorySeeder::class,
            ImmunisationHistorySeeder::class,
            AnthropometrySeeder::class,
            FamilySocialHistorySeeder::class,
            GeneralExaminationSeeder::class,
            SystemicExaminationSeeder::class,
            ReviewOfSystemsSeeder::class,

            TemplateSectionQuestionSeeder::class,
        ]);
    }
}
