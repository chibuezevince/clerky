<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Guarded((['id', 'created_at', 'updated_at']))]
class SectionQuestion extends Model {
    protected $casts = [
        'options' => 'array',
    ];

    protected $with = ['section'];

    public function templates(): BelongsToMany {
        return $this->belongsToMany(ClerkingTemplate::class, 'clerking_template_section_question')
            ->withPivot('order');
    }

    public function section() {
        return $this->belongsTo(ClerkingSection::class, 'clerking_section_id');
    }
}
