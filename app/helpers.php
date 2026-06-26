<?php

use App\Models\Clerking;
use App\Models\ClerkingTemplate;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

function convert_text($input) {
    $words = explode('_', $input);
    $words = array_map('ucfirst', $words);

    return implode(' ', $words);
}

function setting($key, $default = null) {
    if (!Schema::hasTable('settings')) {
        return $default;
    }

    if (is_array($key)) {
        $result = [];

        foreach ($key as $k => $v) {
            Setting::updateOrCreate(
                ['key' => $k],
                ['value' => (string) $v]
            );

            $result[$k] = (string) $v;
        }

        return count($result) === 1 ? reset($result) : $result;
    }

    $value = Setting::where('key', $key)->value('value');

    return $value ?? $default;
}

function sectionsOfferedCompressed($except = []) {
    $templates = ClerkingTemplate::with('sections')->get();
    $sharedSlugs = sharedSectionSlugs($templates);

    return $templates
        ->map(function ($template) use ($sharedSlugs, $except) {
            $sections = $template->sections
                ->reject(fn($section) => $sharedSlugs->contains($section->slug) || in_array($section->slug, $except))
                ->map(fn($section) => "{$section->slug}={$section->name}")
                ->implode(', ');

            return $sections ? "[{$template->id}] {$template->name}: {$sections}" : null;
        })
        ->filter()
        ->implode("\n");
}

function sharedSectionsOffered($except = []) {
    $templates = ClerkingTemplate::with('sections')->get();
    $sharedSlugs = sharedSectionSlugs($templates);

    return $templates->first()->sections
        ->whereIn('slug', $sharedSlugs)
        ->reject(fn($section) => in_array($section->slug, $except))
        ->map(fn($section) => "{$section->slug}={$section->name}")
        ->implode(', ');
}


function sharedSectionSlugs(Collection $templates) {
    return $templates
        ->map(fn($template) => $template->sections->pluck('slug'))
        ->reduce(fn($carry, $slugs) => $carry === null ? $slugs : $carry->intersect($slugs));
}

function compressComplaint(array $complaint) {
    return "{$complaint['key']}:{$complaint['value']}";
}

function clerkingAssistantPrompt(Clerking $clerking, array $chiefComplaint): string {
    $compressedComplaint = compressComplaint($chiefComplaint);

    $ageResponse = $clerking->ageResponse()->first();
    $sexResponse = $clerking->sexResponse()->first();

    $otherComplaints = collect($clerking->presentingComplaints()->answer)
        ->reject(fn($answer) => $answer['key'] === $chiefComplaint['key'])
        ->map(fn($answer) => compressComplaint($answer))
        ->implode('|');

    $age = $ageResponse?->value ?? 'unknown';
    $sex = $sexResponse?->value ?? 'unknown';

    return "Presenting complaint: {$compressedComplaint}. Patient age: {$age} years. Patient sex: {$sex}. Other complaints(s): $otherComplaints";
}