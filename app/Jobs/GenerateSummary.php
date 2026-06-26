<?php

namespace App\Jobs;

use App\Ai\Agents\SummaryGenerator;
use App\Events\Clerk\SummaryReady;
use App\Models\Clerking;
use App\Notifications\User\SummaryGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use function React\Promise\Timer\timeout;

class GenerateSummary implements ShouldQueue {
    use Queueable;

    public function __construct(
        public Clerking $clerking,
    ) {
    }

    public function handle(): void {
        $responses = $this->clerking->responses()
            ->with(['clerkingSection', 'sectionQuestion', 'complaintTemplateQuestion'])
            ->get()
            ->groupBy(fn($response) => $response->clerkingSection->name);

        $prompt = '';
        foreach ($responses as $sectionName => $sectionResponses) {
            $prompt .= "## {$sectionName}\n";
            foreach ($sectionResponses as $response) {
                $question = $response->sectionQuestion?->question
                    ?? $response->complaintTemplateQuestion?->question
                    ?? 'Unknown';
                $prompt .= "Q: {$question}\nA: {$response->formatted_answer}\n";
            }
            $prompt .= "\n";
        }

        try {
            $result = (new SummaryGenerator)->prompt($prompt, provider: Clerking::aiProviders(), timeout: 300);
            
            $summary = $this->clerking->summary;

            $summary->update([
                'content' => $result['summary'],
                'generated_at' => now(),
            ]);

            SummaryReady::dispatch($this->clerking);

            $this->clerking->user->notify(new SummaryGenerated($this->clerking));

        } catch (\Throwable $e) {
            $this->clerking->summary->delete();

            SummaryReady::dispatch($this->clerking, false);

            throw $e;
        }
    }
}
