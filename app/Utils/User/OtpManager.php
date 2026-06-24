<?php

namespace App\Utils\User;

use App\Enums\OtpPurpose;
use App\Models\OTP;
use App\Models\User;
use App\Notifications\User\OtpNotification;

class OtpManager
{
    protected static OTP $otp;

    protected static int $digits = 4;

    public static function setDigit(int $digits)
    {
        static::$digits = $digits;

        return new static;
    }

    public static function create(User $user, OtpPurpose $purpose)
    {
        $user->otps()->latest()->first()?->invalidate();

        $otp = $user->otps()->create([
            'code' => collect(range(1, static::$digits))->map(fn () => random_int(1, 9))->implode(''),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes((int) setting('otp_expiration')),
        ]);

        $otp->load('user');

        static::$otp = $otp;

        return new static;
    }

    public function send(bool $queue = true)
    {
        $queue
            ? static::$otp->user->notify(new OtpNotification(static::$otp)->onQueue('emails'))
            : static::$otp->user->notify(new OtpNotification(static::$otp));

        return static::$otp;
    }

    public static function destroy(User $user, OtpPurpose $purpose)
    {
        $otps = $user->otps()->where('purpose', $purpose)->get();

        foreach ($otps as $otp) {
            $otp->invalidate();
        }
    }
}
