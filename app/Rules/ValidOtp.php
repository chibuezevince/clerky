<?php

namespace App\Rules;

use App\Enums\OtpPurpose;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidOtp implements ValidationRule
{
    public function __construct(public OtpPurpose $purpose, public int $digits = 4) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth()->user();

        if (! $user) {
            $fail('Unauthenticated.');

            return;
        }

        $latestOTP = $user->otps()->where('purpose', $this->purpose)->latest()->first();

        if (! $value) {
            $fail('The :attribute is required.');

            return;
        }

        if (! is_numeric($value)) {
            $fail('The :attribute must be a number.');

            return;
        }

        if (strlen((string) $value) != $this->digits) {
            $fail("The :attribute must be {$this->digits} digits.");

            return;
        }

        if (! $latestOTP || $value != $latestOTP->code) {
            $fail('The :attribute is incorrect.');

            return;
        }

        if ($latestOTP->expires_at->isPast()) {
            $fail('The :attribute has expired.');

            return;
        }
    }
}
