<?php

namespace Database\Seeders;

use App\Models\ClerkingTemplate;
use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class TemplateSectionQuestionSeeder extends Seeder {
    public function run(): void {
        $templates = [
            'medSurg' => ClerkingTemplate::where('slug', 'gen-med-surg')->first(),
            'paediatrics' => ClerkingTemplate::where('slug', 'paediatrics')->first(),
            'obsGyn' => ClerkingTemplate::where('slug', 'obs-gyn')->first(),
        ];

        $sections = [
            'medSurg' => [
                1,
                2,
                3,
                4,
                5,
                6,
                14,
                7,
                8,
                9,
                10,
                11,
                12,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
            ],

            'paediatrics' => [
                1,
                2,
                3,
                6,
                7,
                8,
                14,
                9,
                10,
                11,
                12,
                13,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
            ],

            'obsGyn' => [
                1,
                2,
                3,
                4,
                5,
                6,
                14,
                7,
                8,
                9,
                10,
                11,
                12,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
            ],
        ];

        foreach ($sections as $templateKey => $questions) {
            $attachData = [];

            foreach ($questions as $index => $questionId) {
                $attachData[$questionId] = [
                    'order' => $index + 1,
                ];
            }

            $templates[$templateKey]
                ->sectionQuestions()
                ->attach($attachData);
        }

        $beginPMSHQuestionId = session('begin_pmh_id');
        $endPMSHQuestionId = session('end_pmh_id');

        foreach ($templates as $template) {
            $currentOrder = $template
                ->sectionQuestions()
                ->max('order') + 1;

            for ($i = $beginPMSHQuestionId; $i <= $endPMSHQuestionId; $i++) {
                $template->sectionQuestions()->attach([
                    $i => ['order' => $currentOrder++],
                ]);
            }
        }

        $beginPrenatalId = session('begin_prenatal_id');
        $endPrenatalId = session('end_prenatal_id');

        $paeds = $templates['paediatrics'];
        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginPrenatalId; $i <= $endPrenatalId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $beginNatalId = session('begin_natal_id');
        $endNatalId = session('end_natal_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginNatalId; $i <= $endNatalId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $beginPostnatalId = session('begin_postnatal_id');
        $endPostnatalId = session('end_postnatal_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginPostnatalId; $i <= $endPostnatalId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $beginDevelopmentalId = session('begin_developmental_id');
        $endDevelopmentalId = session('end_developmental_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginDevelopmentalId; $i <= $endDevelopmentalId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++, 'is_required' => false],
            ]);
        }

        $beginNutritionalId = session('begin_nutritional_id');
        $endNutritionalId = session('end_nutritional_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginNutritionalId; $i <= $endNutritionalId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $beginImmunisationId = session('begin_immunisation_id');
        $endImmunisationId = session('end_immunisation_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginImmunisationId; $i <= $endImmunisationId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $beginAnthropometryId = session('begin_anthropometry_id');
        $endAnthropometryId = session('end_anthropometry_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginAnthropometryId; $i <= $endAnthropometryId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++, 'is_required' => false],
            ]);
        }

        $beginFSHId = session('begin_fsh_id');
        $endFSHId = session('end_fsh_id');

        $currentOrder = $paeds->sectionQuestions()->max('order') + 1;

        for ($i = $beginFSHId; $i <= $endFSHId; $i++) {
            $paeds->sectionQuestions()->attach([
                $i => ['order' => $currentOrder++],
            ]);
        }

        $geQuestions = SectionQuestion::where('clerking_section_id', 18)->get();
        $seQuestions = SectionQuestion::where('clerking_section_id', 19)->get();

        foreach ($templates as $template) {
            $order = $template->sectionQuestions()->max('order') + 1;

            foreach ($geQuestions as $q) {
                $template->sectionQuestions()->attach($q->id, [
                    'order' => $order++,
                    'is_required' => false,
                ]);
            }

            foreach ($seQuestions as $q) {
                $template->sectionQuestions()->attach($q->id, [
                    'order' => $order++,
                    'is_required' => false,
                ]);
            }

            $rosQuestions = SectionQuestion::where('clerking_section_id', 17)->get();

            foreach ($rosQuestions as $q) {
                $template->sectionQuestions()->attach($q->id, [
                    'order' => $order++,
                    'is_required' => false,
                ]);
            }
        }
    }
}
