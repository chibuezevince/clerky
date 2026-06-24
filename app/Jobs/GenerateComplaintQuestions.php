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
use Illuminate\Support\Collection;
use Laravel\Ai\Enums\Lab;
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
        $compressedComplaint = compressComplaint($this->complaint);
        $prompt = clerkingAssistantPrompt($this->clerking, $compressedComplaint);

        try {
            $response = new ClerkingAssistant()->prompt(
                $prompt,
                provider: Clerking::aiProviders(),
            );

            $generatedQuestions = $response['questions'];

            if (!count($generatedQuestions)) {
                $this->clerking->update([
                    'is_processing' => false,
                ]);
                SectionQuestionsReady::dispatch($this->clerking->session_id, false);
            } else {
                foreach ($generatedQuestions as $question) {
                    $complaintTemplate = ComplaintTemplate::updateOrCreate([
                        'slug' => Str::slug($this->complaint['key'])
                    ], [
                        'name' => $this->complaint['key'],
                    ]);

                    $section = ClerkingSection::whereSlug($question['section'])->first();

                    $dependentQuestions = [];
                    $order = 0;
                    foreach ($question['questions'] as $sectionQuestion) {
                        $createdQuestion = $complaintTemplate->questions()->create([
                            'clerking_section_id' => $section->id,
                            'question' => $sectionQuestion['value'],
                            'field_key' => $sectionQuestion['field_key'],
                            'input_type' => $sectionQuestion['input_type'],
                            'options' => $sectionQuestion['options'] ?? null,
                            'depends_on_answer' => $sectionQuestion['depends_on_answer'] ?? null,
                            'order' => $order++
                        ]);
                        if (
                            isset($sectionQuestion['depends_on_complaint_question_field_key'])
                            && $sectionQuestion['depends_on_complaint_question_field_key'] !== null
                        )
                            $dependentQuestions[] = [
                                'id' => $createdQuestion->id,
                                'dependent_field_key' => $sectionQuestion['depends_on_complaint_question_field_key']
                            ];
                    }

                    if (count($dependentQuestions)) {
                        foreach ($dependentQuestions as $dependentQuestion) {
                            $questionModel = ComplaintTemplateQuestion::find($dependentQuestion['id']);

                            if ($questionModel === null)
                                continue;

                            $parentQuestion = ComplaintTemplateQuestion::where('field_key', $dependentQuestion['dependent_field_key'])->first();

                            if ($parentQuestion === null)
                                continue;

                            $questionModel->update([
                                'depends_on_complaint_question_id' => $parentQuestion->id
                            ]);
                        }
                    }
                }

                $this->clerking->update([
                    'is_processing' => false
                ]);
                SectionQuestionsReady::dispatch($this->clerking->session_id);
                GetNextSectionQuestions::dispatch($this->clerking, true);
            }
        } catch (\Throwable $th) {
            $this->clerking->update([
                'is_processing' => false
            ]);

            SectionQuestionsReady::dispatch(
                $this->clerking->session_id,
                false,
                $this->previousSent
            );

            throw $th;
        }
    }
}
