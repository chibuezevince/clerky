<?php

namespace App\Models;

use App\Enums\OtpPurpose;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'purpose',
        'expires_at',
    ];

    protected $casts = [
        'code' => 'encrypted',
        'expires_at' => 'datetime',
        'purpose' => OtpPurpose::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invalidate()
    {
        if ($this->expires_at->isFuture()) {
            return $this->update([
                'expires_at' => now(),
            ]);
        }
    }
}
