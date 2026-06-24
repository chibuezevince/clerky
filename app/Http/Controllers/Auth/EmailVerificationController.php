<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Rules\ValidOtp;
use App\Utils\User\OtpManager;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailVerificationController extends Controller
{
    public function index()
    {
        if (request()->user()->email_verified_at) {
            return to_route('dashboard');
        }

        $user = auth()->user();
        $latestOtp = $user->otps()->where('purpose', OtpPurpose::EmailVerification)->latest()->first();

        $otpSentAt = $latestOtp?->created_at;

        return Inertia::render('Auth/VerifyEmail', [
            'otpSentAt' => $otpSentAt,
        ]);
    }

    public function verify(Request $request)
    {

        $request->validate([
            'code' => ['required', new ValidOtp(OtpPurpose::EmailVerification)],
        ]);

        $user = auth()->user();

        $user->markEmailAsVerified();

        Inertia::flash('success', 'Email verified successfully');

        return to_route('dashboard.setup');
    }

    public function resend()
    {
        if (request()->user()->email_verified_at) {
            return to_route('dashboard');
        }
        $user = auth()->user();

        OtpManager::destroy($user, OtpPurpose::EmailVerification);
        OtpManager::create($user, OtpPurpose::EmailVerification)->send();

        Inertia::flash('success', 'Code sent successfully');
    }
}
