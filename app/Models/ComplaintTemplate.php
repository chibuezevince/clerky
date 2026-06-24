<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

#[Guarded('id', 'created_at', 'updated_at')]
class ComplaintTemplate extends Model {
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    protected static function booted(): void {
        static::created(fn() => Cache::forget('complaint_templates_all'));
        static::updated(fn() => Cache::forget('complaint_templates_all'));
    }

    public function clerkingTemplates(): BelongsToMany {
        return $this->belongsToMany(ClerkingTemplate::class);
    }

    public function questions(): HasMany {
        return $this->hasMany(ComplaintTemplateQuestion::class);
    }

    public static function fuzzySearch(string $search, int $maxDistance = 2, int $limit = 5): \Illuminate\Support\Collection {
        $search = Str::lower(trim($search));

        // TODO: Re-add caching with proper cache invalidation
        $candidates = static::select('id', 'name', 'slug', 'is_verified')->get()->toArray();

        return collect($candidates)
            ->map(fn($template) => [
                'template' => $template,
                'distance' => levenshtein($search, Str::lower($template['name'])),
            ])
            ->filter(fn($m) => $m['distance'] <= $maxDistance)
            ->sortBy('distance')
            ->take($limit)
            ->pluck('template')
            ->values();
    }
}
