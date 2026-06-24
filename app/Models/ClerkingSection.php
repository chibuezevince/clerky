<?php

namespace App\Models;

use App\Enums\SectionSlug;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded(['id', 'created_at', 'updated_at'])]
class ClerkingSection extends Model {
    protected $casts = ['is_active' => 'boolean'];

    public function templates(): BelongsToMany {
        return $this->belongsToMany(ClerkingTemplate::class, 'clerking_template_section')
            ->withPivot('order');
    }

    public function questions(): HasMany {
        return $this->hasMany(SectionQuestion::class);
    }

    public function isBioData() {
        $bioData = ClerkingSection::where('slug', SectionSlug::BioData)->first();

        return $this->id === $bioData->id;
    }

    public function isPresentingComplaint() {
        $presentingComplaint = ClerkingSection::where('slug', SectionSlug::PresentingComplaint)->first();

        return $this->id === $presentingComplaint->id;
    }
}
