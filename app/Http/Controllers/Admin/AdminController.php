<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clerking;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller {
    public function index(): \Inertia\Response {
        return Inertia::render('Admin/Index', [
            'total_users' => User::count(),
            'total_clerkings' => Clerking::count(),
            'total_contributors' => User::where('can_contribute', true)->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ]);
    }

    public function users(Request $request): \Inertia\Response {
        $query = User::with('details')
            ->select('id', 'name', 'email', 'username', 'email_verified_at', 'can_contribute', 'created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Admin/Users', [
            'users' => $query->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    public function toggleContributor(User $user): \Illuminate\Http\RedirectResponse {
        $user->update(['can_contribute' => !$user->can_contribute]);

        return back();
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot delete an admin.');
        }

        $user->delete();

        return redirect()->route('admin.users');
    }
}
