<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Clerking;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseController extends Controller {
    function index(Request $request) {
        $search = $request->query('search');

        $query = Clerking::select(['clerkings.id', 'clerkings.session_id', 'clerkings.case_number', 'clerkings.current_question_index', 'clerkings.status', 'clerkings.created_at', 'clerkings.updated_at', 'clerkings.unit_id', 'clerkings.patient_id', 'clerkings.current_section_id'])
            ->with([
                'patient:id,name,age',
                'unit:id,name',
                'currentSection' => fn($query) => $query->select('id', 'name')->withCount('questions'),
                'presentingComplaintResponse' => fn($query) => $query->select('id', 'clerking_id', 'answer', 'complaint_template_question_id', 'section_question_id')->with(['complaintTemplateQuestion:id,input_type', 'sectionQuestion:id,input_type']),
            ])
            ->where('user_id', auth()->id())
            ->withCount('summary')
            ->leftJoin('clerking_sections', 'clerkings.current_section_id', '=', 'clerking_sections.id')
            ->orderByRaw("FIELD(clerkings.status, 'in_progress', 'complete', 'draft')")
            ->orderBy('clerking_sections.name')
            ->orderBy('clerkings.created_at', 'desc');

        if ($search)
            $query->where(fn($query) => $query
                ->where('case_number', 'like', "%{$search}%")
                ->orWhereHas('patient', fn($query) => $query->where('name', 'like', "%{$search}%")));

        $questionCount = fn() => $this->questionCount();

        return Inertia::render('Cases/Index', [
            'cases' => Inertia::scroll($query->paginate(20)),
            'questionCount' => ($questionCount),
        ]);
    }

    function search(Request $request) {
        // TODO
        $searchQuery = $request->query('q');

        if (blank($searchQuery))
            return response()->json([]);

        $results = Clerking::select(['id', 'session_id', 'case_number', 'status', 'unit_id', 'patient_id'])
            ->with(['patient:id,name,age', 'unit:id,name'])
            ->where('user_id', auth()->id())
            ->where('case_number', 'like', "%{$searchQuery}%")
            ->orWhereHas('patient', fn($query) => $query->where('name', 'like', "%{$searchQuery}%"))
            ->limit(6)
            ->get();

        return response()->json($results);
    }

    private function questionCount() {
        $user = User::find(auth()->id());

        $clerkings = $user->clerkings()->get();

        $clerkingQuestionCount = [];
        foreach ($clerkings as $clerking) {
            $questionCount = 0;
            $presentingComplaints = $clerking->complaintTemplates();

            $sectionQuestionCount = $clerking->clerkingTemplate
                ->sectionQuestions()
                ->where('clerking_section_id', $clerking->current_section_id)
                ->count();
            $questionCount += $sectionQuestionCount;

            foreach ($presentingComplaints as $presentingComplaint) {
                $complaintQuestionCount = $presentingComplaint
                    ->questions()
                    ->where('clerking_section_id', $clerking->current_section_id)
                    ->count();
                $questionCount += $complaintQuestionCount;
            }

            $clerkingQuestionCount[$clerking->id] = [
                'count' => $questionCount
            ];
        }

        return $clerkingQuestionCount;
    }
}