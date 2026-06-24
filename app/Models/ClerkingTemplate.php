<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClerkingTemplate extends Model {
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sections(): BelongsToMany {
        return $this->belongsToMany(ClerkingSection::class, 'clerking_template_section')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function units(): BelongsToMany {
        return $this->belongsToMany(Unit::class, 'unit_clerking_template')->withPivot('is_default');
    }

    public function sectionQuestions(): BelongsToMany {
        return $this->belongsToMany(SectionQuestion::class, 'clerking_template_section_question')
            ->withPivot('order', 'is_required')
            ->orderByPivot('order');
    }

    public function questionsForSection(ClerkingSection $section): BelongsToMany {
        return $this->sectionQuestions()
            ->where('clerking_section_id', $section->id);
    }

    public function complaintTemplates(): BelongsToMany {
        return $this->belongsToMany(ComplaintTemplate::class, 'clerking_template_complaint_template');
    }

    public function nextSection(ClerkingSection $currentSection) {
        $currentSection = $this->sections()->find($currentSection->id);

        return $this->sections()
            ->wherePivot('order', '>', $currentSection->pivot->order)
            ->first();
    }
}
