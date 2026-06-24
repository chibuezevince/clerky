<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\Oauth2Controller;
use App\Http\Controllers\Auth\PasswordRecoveryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Home\CaseController;
use App\Http\Controllers\Home\ClerkingController;
use App\Http\Controllers\Home\ContributeController;
use App\Http\Controllers\Home\DonationController;
use App\Http\Controllers\Home\SectionQuestionController;
use App\Http\Controllers\Home\SettingsController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Middleware\EnsureSetupIsCompleted;
use App\Http\Middleware\EnsureUserIsContributor;
use App\Models\Clerking;
use App\Models\ComplaintTemplate;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit')
        ->middleware([HandlePrecognitiveRequests::class]);

    Route::get('/auth/{provider}/redirect', [Oauth2Controller::class, 'redirect'])->name('auth.redirect');

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit')
        ->middleware([HandlePrecognitiveRequests::class, 'throttle:action']);

    Route::get('/logout', [LoginController::class, 'logout'])
        ->withoutMiddleware('guest')
        ->middleware('auth');

    Route::inertia('/forgot-password', 'Auth/Password/Forgot')->name('password.request');
    Route::post('/forgot-password', [PasswordRecoveryController::class, 'send'])->name('password.email')->middleware('throttle:action');
    Route::get('/reset-password/{token}', [PasswordRecoveryController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordRecoveryController::class, 'store'])->name('password.reset.submit')
        ->middleware([HandlePrecognitiveRequests::class]);
});

Route::get('/auth/{provider}/callback', [Oauth2Controller::class, 'callback'])->name('auth.callback');

Route::middleware('auth')->group(function () {
    Route::get('/auth/{provider}/link', [Oauth2Controller::class, 'link'])->name('auth.link');
    Route::delete('/auth/{provider}/unlink', [Oauth2Controller::class, 'unlink'])->name('auth.unlink');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'index'])->name('verification.notice');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('email.resend')->middleware('throttle:action');
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])->name('email.verify.submit');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/setup', [DashboardController::class, 'setup'])->name('dashboard.setup');
    Route::post('/setup', [DashboardController::class, 'completeSetup'])->name('dashboard.setup.submit');

    Route::middleware(EnsureSetupIsCompleted::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/clerk/start', [ClerkingController::class, 'start'])->name('clerk.start');
        Route::get('/clerk/{unit:slug}', [ClerkingController::class, 'redirect'])->name('clerk.redirect');
        Route::get('/clerking/{clerking:session_id}', [ClerkingController::class, 'clerk'])
            ->name('clerking');
        Route::get('/clerking/{clerking:session_id}/summary', [ClerkingController::class, 'summary'])
            ->name('clerking.summary');
        Route::post('/clerking/{clerking:session_id}/summary/generate', [ClerkingController::class, 'generateSummary'])
            ->name('clerking.summary.generate');
        Route::get('/clerking/{clerking:session_id}/summary/edit', [ClerkingController::class, 'summaryEdit'])
            ->name('clerking.summary.edit');
        Route::patch('/clerking/{clerking:session_id}/summary', [ClerkingController::class, 'updateSummary'])
            ->name('clerking.summary.update');
        Route::post('/clerking/{clerking:session_id}/pause', [ClerkingController::class, 'pause'])
            ->name('clerking.pause');
        Route::post('/clerking/{clerking:session_id}/resume', [ClerkingController::class, 'resume'])
            ->name('clerking.resume');
        Route::post('/clerking/{clerking:session_id}/sync', [ClerkingController::class, 'sync'])
            ->name('clerking.sync');
        Route::post('/clerking/{clerking:session_id}/complete', [ClerkingController::class, 'complete'])
            ->name('clerking.complete');


        Route::get('/cases', [CaseController::class, 'index'])->name('cases.all');
        Route::get('/search/clerkings', [CaseController::class, 'search'])->name('search.clerkings');

        Route::get('/contribute', [ContributeController::class, 'index'])->name('contribute');
        Route::get('/contribute/{template:slug}', [ContributeController::class, 'show'])->name('contribute.show');

        Route::middleware(EnsureUserIsContributor::class)->group(function () {
            Route::patch('/contribute/question/reorder', [ContributeController::class, 'reorderQuestions'])->name('contribute.question.reorder');
            Route::post('/contribute/question', [ContributeController::class, 'storeQuestion'])->name('contribute.question.store');
            Route::delete('/contribute/question/{question}', [ContributeController::class, 'destroyQuestion'])->name('contribute.question.destroy');
            Route::patch('/contribute/question/{question}', [ContributeController::class, 'updateQuestion'])->name('contribute.question.update');
            Route::patch('/contribute/questions/{question}/dependency', [ContributeController::class, 'updateDependency'])->name('contribute.question.update-dependency');
        });

        Route::get('/section-questions', [SectionQuestionController::class, 'index'])->name('section-questions.index');
        Route::get('/section-questions/{template:slug}', [SectionQuestionController::class, 'show'])->name('section-questions.show');

        Route::middleware(EnsureUserIsContributor::class)->group(function () {
            Route::post('/section-questions/{template}/question', [SectionQuestionController::class, 'storeQuestion'])->name('section-questions.question.store');
            Route::patch('/section-questions/question/reorder', [SectionQuestionController::class, 'reorderQuestions'])->name('section-questions.question.reorder');
            Route::patch('/section-questions/question/{question}', [SectionQuestionController::class, 'updateQuestion'])->name('section-questions.question.update');
            Route::delete('/section-questions/{template}/question/{question}', [SectionQuestionController::class, 'destroyQuestion'])->name('section-questions.question.destroy');
            Route::patch('/section-questions/question/{question}/dependency', [SectionQuestionController::class, 'updateDependency'])->name('section-questions.question.update-dependency');
        });

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/avatar', [SettingsController::class, 'uploadAvatar'])->name('settings.avatar');
        Route::patch('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password');

        Route::get('/donate', [DonationController::class, 'index'])->name('donate');
    });

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('index');
        Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/toggle-contributor', [\App\Http\Controllers\Admin\AdminController::class, 'toggleContributor'])->name('users.toggle-contributor');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::patch('/notifications/{notification}/read', function (string $id) {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return back();
    })->name('notifications.read');

    Route::patch('/notifications/read-all', function () {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    })->name('notifications.read-all');
});

