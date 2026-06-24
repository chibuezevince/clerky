<?php

namespace App\Jobs;

use App\Events\Clerk\SectionQuestionsReady;
use App\Models\Clerking;
use App\Models\ComplaintTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetNextSectionQuestions implements ShouldQueue {
    use Queueable;

    public function __construct(public Clerking $clerking, public $previousDispatch = false) {
    }

    public function handle(): void {
        $clerking = $this->clerking;
        $presentingComplaint = $clerking->presentingComplaints();

        $anyExists = false;

        foreach ($presentingComplaint->answer as $complaint) {
            $complaintTemplate = ComplaintTemplate::fuzzySearch($complaint['key'])->first();

            if ($complaintTemplate && !$anyExists)
                $anyExists = true;

            if (!$complaintTemplate) {
                if (!$this->clerking->is_processing)
                    $clerking->update(['is_processing' => true]);
                GenerateComplaintQuestions::dispatch($clerking, $complaint, $anyExists);
            }
        }

        if ($anyExists & !$this->previousDispatch) {
            SectionQuestionsReady::dispatch($clerking->session_id);

            $clerking->update([
                'is_processing' => false
            ]);
        }
    }
}