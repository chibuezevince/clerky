<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    protected $guarded = ['id', 'created', 'updated'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
