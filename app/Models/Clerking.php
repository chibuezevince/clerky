<?php

namespace App\Models;

use App\Enums\SectionSlug;
use App\Enums\User\ClerkingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Enums\Lab;

class Clerking extends Model {
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ClerkingStatus::class,
        'started_at' => 'datetime',
        'saved_at' => 'datetime',
        'question_history' => 'array',
        'is_processing' => 'boolean'
    ];

    protected $with = ['patient', 'currentSection'];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function clerkingTemplate() {
        return $this->belongsTo(ClerkingTemplate::class);
    }

    public function summary() {
        return $this->hasOne(Summary::class);
    }

    public function currentSection() {
        return $this->belongsTo(ClerkingSection::class, 'current_section_id');
    }

    public function responses() {
        return $this->hasMany(ClerkingResponse::class);
    }

    public function questions() {
        if (!$this->clerkingTemplate) {
            return collect();
        }

        $sections = $this->clerkingTemplate->sections()->get();
        $getQuestions = fn($clerkingSectionId) => $this->clerkingTemplate
            ->sectionQuestions()
            ->where('clerking_section_id', $clerkingSectionId)
            ->with('section')
            ->get();

        $questions = [];

        $sections->each(function ($section) use (&$questions, $getQuestions) {
            $questions[$section->slug] = $getQuestions($section->id);
        });

        return collect($questions);
    }

    public function complaintTemplates() {
        $presentingComplaint = $this->presentingComplaints();
        $chiefComplaints = [];

        if (!$presentingComplaint)
            return collect();

        foreach ($presentingComplaint->answer as $complaint)
            $chiefComplaints[] = $complaint['key'];


        return ComplaintTemplate::whereIn('name', $chiefComplaints)->get();
    }

    public function allQuestions(): Attribute {
        if (!$this->clerkingTemplate)
            return Attribute::make(fn() => []);

        $complaintTemplates = $this->complaintTemplates();
        $sections = $this->clerkingTemplate->sections;

        $questions = [];
        $ageResponse = $this->ageResponse()->first();
        $sexResponse = $this->sexResponse()->first();

        $sections->each(function ($section) use (&$questions, $complaintTemplates, $sexResponse, $ageResponse) {
            $nativeSectionQuestions = $this->clerkingTemplate->sectionQuestions()
                ->when($sexResponse, fn($query) => $query->whereIn('sex', [$sexResponse->answer, 'both']))
                ->when(
                    $ageResponse,
                    fn($query) => $query
                        ->where(
                            fn($query) => $query
                                ->whereNull('minimum_age')
                                ->orWhere('minimum_age', '<=', $ageResponse->answer)
                        )
                        ->where(
                            fn($query) => $query
                                ->whereNull('maximum_age')
                                ->orWhere('maximum_age', '>=', $ageResponse->answer)
                        )
                )
                ->where('clerking_section_id', $section->id)
                ->get();

            $complaintTemplates->each(function (ComplaintTemplate $complaintTemplate) use ($section, &$questions) {
                $questions = [
                    ...$questions,
                    ...$complaintTemplate->questions()
                        ->where('clerking_section_id', $section->id)
                        ->orderBy('order', 'asc')
                        ->get(),
                ];
            });

            $questions = [...$questions, ...$nativeSectionQuestions];
        });

        $index = 0;
        foreach ($questions as $question)
            $question->sort_index = ++$index;


        $sectionQuestions = [];
        foreach ($sections as $section) {
            $sectionQuestions["sn-$section->id"] = collect($questions)->filter(
                fn($question) => $question->clerking_section_id === $section->id
            )->values()->toArray();
        }

        return Attribute::make(fn() => $sectionQuestions);
    }


    public function mergeQuestions(array|Collection $toMergeQuestions, string $sectionSlug) {
        $clerkingQuestions = $this->questions()->values()->flatten();

        $allQuestions = $clerkingQuestions->flatMap(fn($question) => $question['section']['slug'] === $sectionSlug
            ? [$question, ...$toMergeQuestions]
            : [$question]);

        return $allQuestions;
    }

    public function presentingComplaints() {
        $presentingComplaint = $this->responses()->whereHas(
            'clerkingSection',
            fn($query) => $query->where('slug', SectionSlug::PresentingComplaint)
        )->first();

        if (!$presentingComplaint)
            return null;

        $normalizedAnswer = collect($presentingComplaint->answer)->map(function ($complaint) {
            $template = ComplaintTemplate::fuzzySearch($complaint['key'])->first();

            if (!$template || $template['name'] === $complaint['key'])
                return $complaint;

            return [
                'key' => $template['name'],
                'value' => $complaint['value'],
            ];
        });

        if ($normalizedAnswer->all() !== $presentingComplaint->answer)
            $presentingComplaint->update([
                'answer' => $normalizedAnswer->values()->all(),
            ]);

        return $presentingComplaint->fresh();
    }

    public static function aiProviders() {
        return [
            Lab::OpenRouter->value => 'qwen/qwen3.7-plus',
        ];
    }

    function presentingComplaintResponse() {
        return $this->hasOne(ClerkingResponse::class)->whereHas(
            'clerkingSection',
            fn($query) => $query->where('slug', SectionSlug::PresentingComplaint)
        );
    }

    function ageResponse() {
        return $this->hasOne(ClerkingResponse::class)
            ->whereHas(
                'sectionQuestion',
                fn($query) => $query->where('field_key', 'like', '%age%')
            );
    }

    function sexResponse() {
        return $this->hasOne(ClerkingResponse::class)
            ->whereHas(
                'sectionQuestion',
                fn($query) => $query->where('field_key', 'like', '%sex%')
            );
    }

    function deletePostComplaintResponses(Collection $oldPresentingComplaints) {
        $newPresentingComplaints = $this->complaintTemplates();

        $oldComplaintIds = array_column($oldPresentingComplaints->toArray(), 'id');
        $newComplaintIds = array_column($newPresentingComplaints->toArray(), 'id');

        $matches = array_intersect($oldComplaintIds, $newComplaintIds);

        $postComplaintResponse = $this->responses()->whereHas('complaintTemplateQuestion', fn($query) => $query->whereNotIn('complaint_template_id', $matches))->get();

        $postComplaintResponse->each(fn($response) => $response->delete());
    }
}
