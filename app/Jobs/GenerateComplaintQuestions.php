<?php

namespace App\Jobs;

use App\Ai\Agents\ClerkingAssistant;
use App\Events\Clerk\SectionQuestionsReady;
use App\Models\Clerking;
use App\Models\ClerkingSection;
use App\Models\ComplaintTemplate;
use App\Models\ComplaintTemplateQuestion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Str;

class GenerateComplaintQuestions implements ShouldQueue {
    use Queueable;

    public function __construct(
        public Clerking $clerking,
        public array $complaint,
        public bool $previousSent
    ) {
    }

    public function handle(): void {
        $prompt = clerkingAssistantPrompt($this->clerking, $this->complaint);

        try {
            $response = new ClerkingAssistant()->prompt($prompt, timeout: 300);

            $generatedQuestions = $response['questions'];

            if (!count($generatedQuestions)) {
                $this->clerking->update(['is_processing' => false]);
                SectionQuestionsReady::dispatch($this->clerking->session_id, false);

                return;
            }

            $complaintTemplate = ComplaintTemplate::updateOrCreate([
                'slug' => Str::slug($this->complaint['key']),
            ], [
                'name' => $this->complaint['key'],
            ]);

            $slugs = collect($generatedQuestions)->pluck('section')->unique()->all();
            $sections = ClerkingSection::whereIn('slug', $slugs)->get()->keyBy('slug');

            $order = 0;
            $dependentQuestions = [];

            $rows = [];
            foreach ($generatedQuestions as $question) {
                $section = $sections->get($question['section']);

                if ($section === null) {
                    continue;
                }

                foreach ($question['questions'] as $sectionQuestion) {
                    $rows[] = [
                        'complaint_template_id' => $complaintTemplate->id,
                        'clerking_section_id' => $section->id,
                        'question' => $sectionQuestion['value'],
                        'field_key' => $sectionQuestion['field_key'],
                        'input_type' => $sectionQuestion['input_type'],
                        'options' => isset($sectionQuestion['options']) ? json_encode($sectionQuestion['options']) : null,
                        'depends_on_answer' => $sectionQuestion['depends_on_answer'] ?? null,
                        'order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (
                        isset($sectionQuestion['depends_on_complaint_question_field_key'])
                        && $sectionQuestion['depends_on_complaint_question_field_key'] !== null
                    ) {
                        $dependentQuestions[] = [
                            'field_key' => $sectionQuestion['field_key'],
                            'dependent_field_key' => $sectionQuestion['depends_on_complaint_question_field_key'],
                        ];
                    }
                }
            }

            if (count($rows) === 0) {
                $this->clerking->update(['is_processing' => false]);
                SectionQuestionsReady::dispatch($this->clerking->session_id, false);

                return;
            }

            ComplaintTemplateQuestion::insert($rows);

            if (count($dependentQuestions)) {
                $dependentFieldKeys = collect($dependentQuestions)->pluck('dependent_field_key')->unique()->all();

                $parentsByKey = ComplaintTemplateQuestion::withoutEagerLoads()
                    ->whereIn('field_key', $dependentFieldKeys)
                    ->get()
                    ->keyBy('field_key');

                $childModels = ComplaintTemplateQuestion::withoutEagerLoads()
                    ->whereIn('field_key', collect($dependentQuestions)->pluck('field_key'))
                    ->get()
                    ->keyBy('field_key');

                foreach ($dependentQuestions as $dep) {
                    $parent = $parentsByKey->get($dep['dependent_field_key']);
                    $child = $childModels->get($dep['field_key']);

                    if ($parent === null || $child === null) {
                        continue;
                    }

                    $child->update(['depends_on_complaint_question_id' => $parent->id]);
                }
            }

            $this->clerking->update(['is_processing' => false]);
            
            SectionQuestionsReady::dispatch($this->clerking->session_id, true, $this->previousSent);
        } catch (\Throwable $th) {
            $this->clerking->update(['is_processing' => false]);

            SectionQuestionsReady::dispatch(
                $this->clerking->session_id,
                false,
                $this->previousSent,
            );

            throw $th;
        }
    }
}
