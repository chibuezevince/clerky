<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RegistrationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNotNull('password'),
            ],
            'password' => 'required|string|min:8',
        ];
    }

    protected function withValidator(Validator $validator): void {
        $validator->after(function (Validator $validator) {
             if ($validator->errors()->isNotEmpty()) return;

            $existingUser = User::where('email', $this->email)->whereNull('password')->first();

            if ($existingUser) {
                $provider = $existingUser->socialAccounts->first()?->provider;

                $this->validator->errors()->add(
                    'email',
                    sprintf(
                        'This email is linked to a %s account. Sign in with %s or use "Forgot Password" to set a password.',
                        ucfirst($provider ?? 'social'),
                        ucfirst($provider ?? 'social'),
                    ),
                );
            }
        });
    }
}
