<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use App\Utils\User\OtpManager;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RegisterController extends Controller {
    public function submit(RegistrationRequest $request) {
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());

            OtpManager::create($user, OtpPurpose::EmailVerification)->send();

            $request->session()->regenerate();

            auth()->guard()->login($user);

            DB::commit();

            Inertia::encryptHistory();

            return to_route('email.verify');

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'default' => 'Something went wrong, please try again later.',
            ]);
        }
    }
}
