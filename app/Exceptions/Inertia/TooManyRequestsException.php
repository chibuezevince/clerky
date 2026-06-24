<?php

namespace App\Exceptions\Inertia;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TooManyRequestsException extends Exception
{
    public function render(Request $request)
    {
        Inertia::flash('error', $this->getMessage());

        if ($request->email) {
            throw ValidationException::withMessages([
                'email' => 'Too many attempts. Try again later.',
            ]);
        }

        return response()->noContent();
    }
}
