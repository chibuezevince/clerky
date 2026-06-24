<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(ClerkingTemplate::class, 'unit_clerking_template')->withPivot('is_default');
    }

    public function clerkings(): HasMany
    {
        return $this->hasMany(Clerking::class);
    }
}
