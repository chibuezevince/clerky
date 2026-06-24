<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\AcademicLevel;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller {
    function index() {
        $user = auth()->user()->load('details');

        return Inertia::render('Settings/Index', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'can_contribute' => $user->can_contribute,
                'details' => $user->details ? [
                    'avatar' => $user->details->avatar,
                    'academic_level_id' => $user->details->academic_level_id,
                    'institution_id' => $user->details->institution_id,
                ] : null,
                'social_accounts' => $user->socialAccounts->map(fn($a) => [
                    'id' => $a->id,
                    'provider' => $a->provider,
                    'avatar' => $a->avatar,
                ]),
            ],
            'institutions' => Institution::where('is_active', true)->select('id', 'name')->get(),
            'levels' => AcademicLevel::where('is_active', true)->select('id', 'label')->get(),
        ]);
    }

    function update(Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_level_id' => ['nullable', 'exists:academic_levels,id'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
        ]);

        $user->update(['name' => $validated['name']]);

        $details = [];

        if ($request->has('academic_level_id'))
            $details['academic_level_id'] = $validated['academic_level_id'] ?: null;

        if ($request->has('institution_id'))
            $details['institution_id'] = $validated['institution_id'] ?: null;

        if (!empty($details))
            $user->details()->updateOrCreate(
                ['user_id' => $user->id],
                $details,
            );

        return back();
    }

    function uploadAvatar(Request $request) {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth()->user()->load('details');

        if ($user->details?->avatar) {
            $oldPath = str_replace('/storage/', '', $user->details->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $path = '/storage/' . $request->file('avatar')->store('avatars', 'public');

        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => $path],
        );

        return back();
    }

    function changePassword(Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => $validated['new_password'],
        ]);

        return back();
    }
}
