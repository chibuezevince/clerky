<?php

namespace App\Http\Controllers\Home;

use App\Enums\User\ClerkingStatus;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateSummary;
use App\Jobs\GetNextSectionQuestions;
use App\Models\Clerking;
use App\Models\ClerkingSection;
use App\Models\Patient;
use App\Models\SectionQuestion;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ClerkingController extends Controller {
    function start() {
        $units = fn() => Unit::where('is_active', true)->get();

        return Inertia::render('Clerking/Start', compact('units'));
    }

    function redirect(Unit $unit) {
        $defaultTemplate = $unit
            ->templates()
            ->where('is_default', true)
            ->first();

        $firstSection = $defaultTemplate->sections()->first();


        $clerking = $unit->clerkings()->create([
            'user_id' => Auth::id(),
            'clerking_template_id' => $defaultTemplate->id,
            'current_section_id' => $firstSection->id,
            'session_id' => Str::uuid(),
            'case_number' => Str::upper(Str::random(2)) . '-' . random_int(1000, 9999) . '-' . random_int(1000, 9999),
            'started_at' => now(),
        ]);

        return to_route('clerking', $clerking);
    }

    function clerk(Clerking $clerking) {
        if ($clerking->status === ClerkingStatus::Complete)
            return to_route('clerking.summary', $clerking);

        $clerking->load([
            'unit:id,name,slug,description,is_active',
            'clerkingTemplate.sections:id,name,slug,description,is_active,complaint_question_position',
        ]);

        $answeredQuestions = $clerking->responses()->select(
            'id',
            'answer as value',
            'clerking_section_id',
            'section_question_id',
            'complaint_template_question_id as complaint_question_id',
        )->get();

        return Inertia::render('Clerking/Index', [
            'unit' => $clerking->unit,
            'clerking' => fn() => $clerking->unsetRelation('unit')->append('all_questions'),
            'answeredQuestions' => $answeredQuestions,
            'sections' => $clerking->clerkingTemplate->sections,
        ]);
    }

    function pause(Clerking $clerking, Request $request) {
        $request->validate([
            'elapsed_seconds' => 'required',
        ]);

        $clerking->update([
            'elapsed_seconds' => $request->elapsed_seconds,
            'started_at' => null,
        ]);

        return back();
    }

    function resume(Clerking $clerking, Request $request) {
        $request->validate([
            'started_at' => 'required|date',
        ]);

        $clerking->update([
            'started_at' => $request->started_at,
        ]);

        return back();
    }

    public function sync(Request $request, Clerking $clerking) {
        $request->validate([
            'answer' => 'sometimes|nullable',
            'question_id' => 'sometimes|required_with:answer|integer',
            'is_complaint_question' => 'sometimes|boolean',
            'clerking_section_id' => 'sometimes|integer|exists:clerking_sections,id',
            'current_question_index' => 'sometimes|numeric',
            'question_history' => 'sometimes|array',
            'question_history.*' => 'integer',
            'current_section_id' => 'sometimes|integer|exists:clerking_sections,id',
        ]);

        if ($request->exists('answer') && $request->filled('question_id')) {
            $isComplaintQuestion = $request->boolean('is_complaint_question');

            if (!$isComplaintQuestion) {
                if ($request->input('answer') !== null) {
                    $this->storePatient(
                        $clerking,
                        $request->question_id,
                        $request->answer,
                    );
                }

                $clerking->responses()->updateOrCreate([
                    'section_question_id' => $request->question_id,
                ], [
                    'clerking_section_id' => $request->clerking_section_id,
                    'complaint_template_question_id' => null,
                    'answer' => $request->answer,
                ]);
            } else {
                $clerking->responses()->updateOrCreate([
                    'complaint_template_question_id' => $request->question_id,
                ], [
                    'clerking_section_id' => $request->clerking_section_id,
                    'section_question_id' => null,
                    'answer' => $request->answer,
                ]);
            }

            if (!$isComplaintQuestion) {
                $savedQuestion = SectionQuestion::find($request->question_id);
                if ($savedQuestion && $savedQuestion->section->isPresentingComplaint())
                    GetNextSectionQuestions::dispatch($clerking);
            }
        }

        if ($request->has('current_question_index'))
            $clerking->update($request->only('current_question_index', 'question_history'));

        if ($request->has('current_section_id')) {
            $clerking->update(['current_section_id' => $request->current_section_id]);
            $clerking->refresh();
        }

        return back();
    }

    private function storePatient(Clerking $clerking, int $questionId, string|array $answer) {
        $patientFields = Patient::fields();
        $sectionQuestion = SectionQuestion::find($questionId);

        if (
            !$clerking->currentSection->isBioData() ||
            !in_array($sectionQuestion->field_key, $patientFields)
        )
            return;


        if ($clerking->patient) {

            $clerking->patient->update([
                $sectionQuestion->field_key => $answer
            ]);

        } else {
            $patient = Patient::create([
                'user_id' => $clerking->user_id,
                $sectionQuestion->field_key => $answer,
            ]);

            $clerking->update([
                'patient_id' => $patient->id,
            ]);
        }
    }

    function complete(Clerking $clerking) {
        $clerking->update([
            'status' => ClerkingStatus::Complete,
            'completed_at' => now(),
        ]);

        return to_route('clerking.summary', $clerking);
    }

    function summary(Clerking $clerking) {
        $responses = $clerking->responses()
            ->with(['clerkingSection', 'sectionQuestion', 'complaintTemplateQuestion'])
            ->get()
            ->groupBy(fn($response) => $response->clerkingSection->name)
            ->map(fn($group) => $group->map(fn($response) => [
                'id' => $response->id,
                'value' => $response->formatted_answer,
                'question' => $response->sectionQuestion?->question ?? $response->complaintTemplateQuestion?->question ?? 'Unknown',
                'input_type' => $response->sectionQuestion?->input_type ?? $response->complaintTemplateQuestion?->input_type,
            ]));

        return Inertia::render('Clerking/Summary', [
            'clerking' => $clerking,
            'responses' => $responses,
            'unit' => $clerking->unit,
            'summary' => $clerking->summary
        ]);
    }

    function generateSummary(Clerking $clerking) {
        $summary = $clerking->summary()->firstOrNew();

        if (!$summary->exists)
            $summary->save();

        if ($summary->content !== null)
            return back();

        GenerateSummary::dispatch($clerking);

        return back();
    }

    function summaryEdit(Clerking $clerking) {
        return Inertia::render('Clerking/SummaryEdit', [
            'clerking' => $clerking,
            'unit' => $clerking->unit,
            'summary' => $clerking->summary,
        ]);
    }

    function updateSummary(Clerking $clerking, Request $request) {
        $data = $request->validate([
            'edited_content' => 'required|string',
        ]);

        $clerking->summary->update($data);

        return back();
    }
}
