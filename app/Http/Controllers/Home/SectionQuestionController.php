<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ClerkingSection;
use App\Models\ClerkingTemplate;
use App\Models\SectionQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SectionQuestionController extends Controller {
    function index() {
        $templates = ClerkingTemplate::orderBy('name')
            ->withCount('sectionQuestions')
            ->get();

        return Inertia::render('SectionQuestions/Index', [
            'templates' => $templates->map(fn($template) => [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
                'description' => $template->description,
                'questions_count' => $template->section_questions_count,
            ]),
        ]);
    }

    function show(ClerkingTemplate $template) {
        $template->load(['sectionQuestions' => fn($q) => $q->withPivot('order')->with('section:id,name')]);

        $sections = $template->sectionQuestions
            ->map(fn(SectionQuestion $q) => [
                ...$q->toArray(),
                'order' => (int) $q->pivot->order,
            ])
            ->groupBy(fn($q) => $q['section']['name']);

        $canContribute = auth()->user()->can_contribute;
        $clerkingSections = fn() => ClerkingSection::where('is_active', true)->get();

        return Inertia::render('SectionQuestions/Show', [
            'template' => $template->only('id', 'name', 'slug'),
            'sections' => $sections,
            'canContribute' => $canContribute,
            'clerkingSections' => Inertia::once($clerkingSections),
        ]);
    }

    function storeQuestion(Request $request, ClerkingTemplate $template) {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'order' => 'required|integer|min:1',
            'input_type' => 'required|string|in:text,textarea,select,boolean,scale,multi_select,number,key_value',
            'section_id' => 'required|integer|exists:clerking_sections,id',
            'depends_on_question_id' => 'nullable|integer|exists:section_questions,id',
            'depends_on_answer' => 'nullable|string|max:255',
            'config' => 'nullable|array',
            'config.options' => 'nullable|array',
            'sex' => 'nullable|in:male,female,both',
            'minimum_age' => 'nullable|numeric',
            'maximum_age' => 'nullable|numeric',
        ]);

        $section = ClerkingSection::findOrFail($validated['section_id']);

        $config = $validated['config'] ?? [];

        $optionTypes = ['select', 'multi_select'];
        if (!empty($config['options']) && in_array($validated['input_type'], $optionTypes) && is_array($config['options'])) {
            $config['options'] = collect($config['options'])
                ->filter()
                ->mapWithKeys(fn($label) => [Str::slug($label, '_') => $label])
                ->toArray();
        }

        $question = SectionQuestion::create([
            'clerking_section_id' => $section->id,
            'contributor_id' => auth()->id(),
            'question' => $validated['question'],
            'field_key' => Str::slug($validated['question'], '_') . '-' . random_int(10000000, 99999999),
            'input_type' => $validated['input_type'],
            'depends_on_section_question_id' => $validated['depends_on_question_id'] ?? null,
            'depends_on_answer' => $validated['depends_on_answer'] ?? null,
            'options' => $config['options'] ?? null,
            'sex' => $validated['sex'],
            'minimum_age' => $validated['minimum_age'] ?? null,
            'maximum_age' => $validated['maximum_age'] ?? null,
        ]);

        $template->sectionQuestions()->attach($question->id, [
            'order' => $validated['order'],
        ]);

        return back();
    }

    function updateQuestion(Request $request, SectionQuestion $question) {
        $validated = $request->validate([
            'question' => ['sometimes', 'required', 'string', 'max:500'],
            'input_type' => ['sometimes', 'required', 'string', 'in:text,textarea,select,boolean,scale,multi_select,number,key_value'],
            'section_id' => ['sometimes', 'required', 'integer', 'exists:clerking_sections,id'],
            'depends_on_question_id' => ['nullable', 'integer', 'exists:section_questions,id'],
            'depends_on_answer' => ['nullable', 'string', 'max:255'],
            'template_id' => ['sometimes', 'required', 'integer', 'exists:clerking_templates,id'],
            'order' => ['sometimes', 'required', 'integer', 'min:1'],
            'config' => ['nullable', 'array'],
            'config.options' => ['nullable', 'array'],
            'sex' => ['nullable', 'in:male,female,both'],
            'minimum_age' => ['nullable', 'numeric'],
            'maximum_age' => ['nullable', 'numeric'],
            'max_char' => ['nullable', 'numeric']
        ]);
        $data = [];

        if ($request->has('question'))
            $data['question'] = $validated['question'];

        if ($request->has('input_type'))
            $data['input_type'] = $validated['input_type'];

        if ($request->has('section_id')) {
            $section = ClerkingSection::findOrFail($validated['section_id']);
            $data['clerking_section_id'] = $section->id;
        }

        if ($request->has('depends_on_question_id'))
            $data['depends_on_section_question_id'] = $validated['depends_on_question_id'];

        if ($request->has('depends_on_answer'))
            $data['depends_on_answer'] = $validated['depends_on_answer'];

        if ($request->has('sex'))
            $data['sex'] = $validated['sex'];

        if ($request->has('minimum_age'))
            $data['minimum_age'] = $validated['minimum_age'];

        if ($request->has('maximum_age'))
            $data['maximum_age'] = $validated['maximum_age'];

        $data['max_char'] = 255;
        if ($request->max_char)
            $data['max_char'] = $validated['max_char'];

        if ($request->has('config')) {
            $config = $validated['config'] ?? [];

            $optionTypes = ['select', 'multi_select'];
            if (!empty($config['options']) && in_array($validated['input_type'] ?? $question->input_type, $optionTypes) && is_array($config['options'])) {
                $data['options'] = collect($config['options'])
                    ->filter()
                    ->mapWithKeys(fn($label) => [Str::slug($label, '_') => $label])
                    ->toArray();
            } elseif ($request->has('config') && empty($config['options'])) {
                $data['options'] = null;
            }
        }

        $question->update($data);

        if ($request->has('order') && $request->has('template_id')) {
            $newOrder = (int) $validated['order'];
            $templateId = (int) $validated['template_id'];

            $template = ClerkingTemplate::findorFail($templateId);

            $existingPivot = $template->sectionQuestions()
                ->where('section_question_id', $question->id)
                ->first();

            $oldOrder = $existingPivot?->pivot->order ?? $newOrder;
            $sectionId = $request->has('section_id')
                ? ClerkingSection::findOrFail($validated['section_id'])->id
                : $question->clerking_section_id;

            if ($newOrder !== $oldOrder) {
                $questionsInSection = $template->sectionQuestions()
                    ->where('clerking_section_id', $sectionId)
                    ->orderByPivot('order')
                    ->get();

                $currentQuestionInSection = $questionsInSection->firstWhere('id', $question->id);

                if ($currentQuestionInSection) {
                    $template->sectionQuestions()->updateExistingPivot($question->id, [
                        'order' => $newOrder,
                    ]);
                }
            } else {
                $template = ClerkingTemplate::findOrFail($templateId);
                $template->sectionQuestions()->updateExistingPivot($question->id, [
                    'order' => $newOrder,
                ]);
            }
        }

        return back();
    }

    function destroyQuestion(ClerkingTemplate $template, SectionQuestion $question) {
        $template->sectionQuestions()->detach($question->id);

        return back();
    }

    function reorderQuestions(Request $request) {
        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'exists:clerking_templates,id'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['required', 'integer', 'exists:section_questions,id'],
            'questions.*.order' => ['required', 'integer', 'min:1'],
        ]);

        $template = ClerkingTemplate::findOrFail($validated['template_id']);

        foreach ($validated['questions'] as $item) {
            $template->sectionQuestions()->updateExistingPivot($item['id'], [
                'order' => $item['order'],
            ]);
        }

        return back();
    }

    function updateDependency(SectionQuestion $question, Request $request) {
        $request->validate([
            'depends_on_section_question_id' => 'nullable|exists:section_questions,id',
            'depends_on_answer' => 'nullable|string',
        ]);

        $data = [];

        if ($request->has('depends_on_section_question_id')) {
            $data['depends_on_section_question_id'] = $request->depends_on_section_question_id;
            if (is_null($request->depends_on_section_question_id))
                $data['depends_on_answer'] = null;
        }

        if ($request->has('depends_on_answer')) {
            $dependsOn = $request->depends_on_section_question_id
                ?? $question->depends_on_section_question_id;

            if ($dependsOn)
                $data['depends_on_answer'] = $request->depends_on_answer;
        }

        $question->update($data);

        return back();
    }
}
