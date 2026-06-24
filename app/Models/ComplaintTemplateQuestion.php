<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded('id')]
class ComplaintTemplateQuestion extends Model {

    protected $casts = [
        'options' => 'array',
    ];

    protected $with = ['section'];
    
    public function complaintTemplate(): BelongsTo {
        return $this->belongsTo(ComplaintTemplate::class);
    }

    public function section(): BelongsTo {
        return $this->belongsTo(ClerkingSection::class, 'clerking_section_id');
    }
}
