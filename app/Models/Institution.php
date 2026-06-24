<?php

namespace App\Models;

use App\Enums\InstitutionType;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'type' => InstitutionType::class,
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
