<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'email_verified_at', 'can_contribute'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail {
    use Notifiable;

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_contribute' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function otps() {
        return $this->hasMany(Otp::class);
    }

    public function details() {
        return $this->hasOne(UserDetail::class);
    }

    public function socialAccounts(): HasMany {
        return $this->hasMany(SocialAccount::class);
    }

    public function hasCompletedSetup(): bool {
        $this->load('details');

        return $this->details?->academic_level_id && $this->details?->institution_id;
    }

    public function clerkings() {
        return $this->hasMany(Clerking::class);
    }

    public function clerkingSummary() {
        return $this->hasOneThrough(Summary::class, Clerking::class);
    }
}
