<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ClerkingSection;
use App\Models\ComplaintTemplate;
use App\Models\ComplaintTemplateQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ContributeController extends Controller {
    function index(Request $request) {
        $search = $request->query('search');

        $query = ComplaintTemplate::orderBy('name')
            ->withCount('questions');

        if ($search)
            $query->where('name', 'like', "%{$search}%");

        return Inertia::render('Contribute/Index', [
            'complaintTemplates' => Inertia::scroll($query->paginate(20)),
        ]);
    }

    function show(ComplaintTemplate $template) {
        $template->load(['questions' => fn($q) => $q->orderBy('order')->with('section:id,name')]);
        $sections = $template->questions->groupBy(fn($query) => $query->section->name);
        $canContribute = auth()->guard()->user()->can_contribute;

        $clerkingSections = fn() => ClerkingSection::where('is_active', true)->get();
        return Inertia::render('Contribute/Show', [
            'template' => $template->only('id', 'name', 'slug', 'is_verified'),
            'sections' => $sections,
            'canContribute' => $canContribute,
            'clerkingSections' => Inertia::once($clerkingSections),
        ]);
    }

    function updateQuestion(Request $request, ComplaintTemplateQuestion $question) {
        $validated = $request->validate([
            'question' => ['sometimes', 'required', 'string', 'max:500'],
            'input_type' => ['sometimes', 'required', 'string', 'in:text,textarea,select,boolean,scale,multi_select,number,key_value'],
            'section_id' => ['sometimes', 'required', 'integer', 'exists:clerking_sections,id'],
            'depends_on_question_id' => ['nullable', 'integer', 'exists:complaint_template_questions,id'],
            'depends_on_answer' => ['nullable', 'string', 'max:255'],
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
            $data['depends_on_complaint_question_id'] = $validated['depends_on_question_id'];

        if ($request->has('depends_on_answer'))
            $data['depends_on_answer'] = $validated['depends_on_answer'];

        if ($request->has('sex'))
            $data['sex'] = $validated['sex'];

        if ($request->has('minimum_age'))
            $data['minimum_age'] = $validated['minimum_age'];

        if ($request->has('maximum_age'))
            $data['maximum_age'] = $validated['maximum_age'];

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

        $sectionChanged = $request->has('section_id');
        $targetSectionId = $sectionChanged
            ? ClerkingSection::findOrFail($validated['section_id'])->id
            : $question->clerking_section_id;

        if ($request->has('order')) {
            $newOrder = (int) $validated['order'];
            $oldOrder = $question->order;
            $oldSectionId = $question->clerking_section_id;
            $sameSection = !$sectionChanged || $oldSectionId === $targetSectionId;

            if ($sameSection && $newOrder !== $oldOrder) {
                if ($newOrder < $oldOrder) {
                    ComplaintTemplateQuestion::where('complaint_template_id', $question->complaint_template_id)
                        ->where('clerking_section_id', $oldSectionId)
                        ->whereBetween('order', [$newOrder, $oldOrder - 1])
                        ->increment('order');
                } else {
                    ComplaintTemplateQuestion::where('complaint_template_id', $question->complaint_template_id)
                        ->where('clerking_section_id', $oldSectionId)
                        ->whereBetween('order', [$oldOrder + 1, $newOrder])
                        ->decrement('order');
                }
            }

            $data['order'] = $newOrder;
        }

        $data['max_char'] = 255;
        
        if ($request->max_char)
            $data['max_char'] = $validated['max_char'];

        $question->update($data);

        return back();
    }

    function reorderQuestions(Request $request) {
        $validated = $request->validate([
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['required', 'integer', 'exists:complaint_template_questions,id'],
            'questions.*.order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['questions'] as $item)
            ComplaintTemplateQuestion::where('id', $item['id'])->update(['order' => $item['order']]);

        return back();
    }

    public function updateDependency(ComplaintTemplateQuestion $question, Request $request) {
        $request->validate([
            'depends_on_complaint_question_id' => 'nullable|exists:complaint_template_questions,id',
            'depends_on_answer' => 'nullable|string',
        ]);

        $data = [];

        if ($request->has('depends_on_complaint_question_id')) {
            $data['depends_on_complaint_question_id'] = $request->depends_on_complaint_question_id;
            if (is_null($request->depends_on_complaint_question_id))
                $data['depends_on_answer'] = null;
        }

        if ($request->has('depends_on_answer')) {
            $dependsOn = $request->depends_on_complaint_question_id
                ?? $question->depends_on_complaint_question_id;

            if ($dependsOn)
                $data['depends_on_answer'] = $request->depends_on_answer;
        }

        $question->update($data);

        return back();
    }

    function storeQuestion(Request $request) {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:complaint_templates,id',
            'question' => 'required|string|max:500',
            'order' => 'required|integer|min:1',
            'input_type' => 'required|string|in:text,textarea,select,boolean,scale,multi_select,number,key_value',
            'section_id' => 'required|integer|exists:clerking_sections,id',
            'depends_on_question_id' => 'nullable|integer|exists:complaint_template_questions,id',
            'depends_on_answer' => 'nullable|string|max:255',
            'config' => 'nullable|array',
            'config.options' => 'nullable|array',
            'config.min' => 'nullable|numeric',
            'config.max' => 'nullable|numeric',
            'sex' => 'nullable|in:male,female,both',
            'minimum_age' => 'nullable|numeric',
            'maximum_age' => 'nullable|numeric',
        ]);

        $section = ClerkingSection::findOrFail($validated['section_id']);

        $config = $validated['config'] ?? [];

        $optionTypes = ['select', 'multi_select'];
        if (!empty($config['options']) && in_array($validated['input_type'], $optionTypes) && is_array($config['options']))
            $config['options'] = collect($config['options'])
                ->filter()
                ->mapWithKeys(fn($label) => [Str::slug($label, '_') => $label])
                ->toArray();

        ComplaintTemplateQuestion::where('clerking_section_id', $section->id)
            ->where('complaint_template_id', $validated['template_id'])
            ->increment('order');

        ComplaintTemplateQuestion::create([
            'complaint_template_id' => $validated['template_id'],
            'clerking_section_id' => $section->id,
            'question' => $validated['question'],
            'field_key' => Str::slug($validated['question'], '_') . '-' . random_int(10000000, 99999999),
            'input_type' => $validated['input_type'],
            'order' => $validated['order'],
            'depends_on_complaint_question_id' => $validated['depends_on_question_id'] ?? null,
            'depends_on_answer' => $validated['depends_on_answer'] ?? null,
            'options' => $config['options'] ?? null,
            'sex' => $validated['sex'],
            'minimum_age' => $validated['minimum_age'] ?? null,
            'maximum_age' => $validated['maximum_age'] ?? null,
        ]);

        return back();
    }

    function destroyQuestion(ComplaintTemplateQuestion $question) {
        $question->delete();

        return back();
    }
}
