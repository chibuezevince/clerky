<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Summary extends Model {
    protected $guarded = ['id'];

    protected $casts = [
        'is_confirmed' => 'boolean',
        'generated_at' => 'datetime',
    ];

    public function clerking(): BelongsTo {
        return $this->belongsTo(Clerking::class);
    }
}
