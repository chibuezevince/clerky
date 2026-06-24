<?php

namespace App\Http\Controllers\Home;

use App\Enums\User\ClerkingStatus;
use App\Http\Controllers\Controller;
use App\Models\AcademicLevel;
use App\Models\Clerking;
use App\Models\Institution;
use Flowframe\Trend\Trend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Str;

class DashboardController extends Controller {
    public function setup() {
        if (!request()->headers->has('X-Inertia-Complete-Setup') && request()->user()->hasCompletedSetup()) {
            return to_route('dashboard');
        }

        return Inertia::render('Dashboard/Setup', [
            'institutions' => Institution::where('is_active', true)->select('id', 'name')->get(),
            'levels' => AcademicLevel::where('is_active', true)->select('id', 'label')->get(),
        ]);
    }

    public function completeSetup(Request $request) {
        if (!$request->headers->has('X-Inertia-Complete-Setup') && $request->user()->hasCompletedSetup()) {
            return to_route('dashboard');
        }

        $request->validate([
            'level' => 'required|exists:academic_levels,id',
            'institution' => 'required|exists:institutions,id',
        ]);

        auth()->user()->details()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'academic_level_id' => $request->level,
            'institution_id' => $request->institution,
        ]);

        Inertia::flash('success', 'Welcome to the dashboard!');

        return to_route('dashboard');
    }

    public function index(Request $request) {
        $user = auth()->user();
        Inertia::encryptHistory();

        $now = now();
        $thirtyDaysAgo = now()->subDays(30);
        $sixtyDaysAgo = now()->subDays(60);

        $casesClerked = $user->clerkings()->count();
        $totalCompleted = $user->clerkings()->where('status', ClerkingStatus::Complete)->count();

        $clerkedCurrent = $user->clerkings()
            ->whereBetween('created_at', [$thirtyDaysAgo, $now])
            ->count();

        $clerkedPrevious = $user->clerkings()
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $rateCurrent = $user->clerkings()->where('status', ClerkingStatus::Complete)
            ->whereBetween('created_at', [$thirtyDaysAgo, $now])
            ->count();

        $ratePrevious = $user->clerkings()->where('status', ClerkingStatus::Complete)
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $summariesCurrent = $user->clerkingSummary()
            ->whereBetween('summaries.created_at', [$thirtyDaysAgo, $now])
            ->count();

        $summariesPrevious = $user->clerkingSummary()
            ->whereBetween('summaries.created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $averageTimeCurrent = $user->clerkings()
            ->where('status', ClerkingStatus::Complete)
            ->whereNotNull('completed_at')
            ->whereBetween('created_at', [$thirtyDaysAgo, $now])
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, completed_at)'));

        $averageTimeCurrent = $averageTimeCurrent ? $averageTimeCurrent / 60 : 0;

        $averageTimePrevious = $user->clerkings()
            ->where('status', ClerkingStatus::Complete)
            ->whereNotNull('completed_at')
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, completed_at)'));

        $averageTimePrevious = $averageTimePrevious ? $averageTimePrevious / 60 : 0;

        $clerkedDelta = $clerkedPrevious > 0
            ? round((($clerkedCurrent - $clerkedPrevious) / $clerkedPrevious) * 100)
            : null;

        $rateDelta = $ratePrevious > 0
            ? round((($rateCurrent - $ratePrevious) / $ratePrevious) * 100)
            : null;

        $summariesDelta = $summariesPrevious > 0
            ? round((($summariesCurrent - $summariesPrevious) / $summariesPrevious) * 100)
            : null;

        $avgDelta = $averageTimePrevious > 0
            ? round((($averageTimeCurrent - $averageTimePrevious) / $averageTimePrevious) * 100)
            : null;

        $period = $request->period ?? '1M';

        $oldestDate = $user->clerkings()->oldest('created_at')->value('created_at') ?? $now;

        $startDate = match ($period) {
            '1W' => now()->subDays(7),
            '1M' => now()->subDays(30),
            '1Y' => now()->subDays(365),
            'MAX' => $oldestDate,
        };

        $latestClerkings = $user->clerkings()
            ->select(['id', 'session_id', 'unit_id', 'patient_id', 'status', 'case_number', 'created_at', 'completed_at'])
            ->with(['unit:id,name,icon,slug', 'summary', 'patient:id,age,sex'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'casesClerked' => $casesClerked,
            'clerkedDelta' => $clerkedDelta,
            'completionRate' => Inertia::once(fn() => $casesClerked
                ? $totalCompleted / $casesClerked * 100
                : 0),
            'rateDelta' => $rateDelta,
            'summaries' => Inertia::once(fn() => $user->clerkingSummary()->count()),
            'summariesDelta' => $summariesDelta,
            'avgCompletionTime' => Inertia::once(function () use ($user) {
                $avg = $user->clerkings()
                    ->where('status', ClerkingStatus::Complete)
                    ->whereNotNull('completed_at')
                    ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, completed_at)'));

                return $avg ? $avg / 60 : 0;
            }),
            'avgDelta' => $avgDelta,
            'trend' => Inertia::once(fn() => Trend::query(
                Clerking::query()->where('user_id', $user->id)
            )->between(start: $startDate, end: now())->perDay()->count()),
            'activePeriod' => $period,
            'lastClerking' => Inertia::once(fn() => $user->clerkings()
                ->select(['id', 'session_id', 'unit_id', 'patient_id', 'status', 'case_number', 'created_at', 'completed_at'])
                ->with(['unit:id,name,icon,slug', 'summary', 'patient:id,age,sex'])
                ->latest()
                ->first()),
            'pendingSummaries' => $user->clerkings()
                ->where('status', ClerkingStatus::Complete)
                ->where(fn($query) => $query->whereDoesntHave('summary')->orWhereHas('summary', fn($q) => $q->whereNull('content')))
                ->select(['id', 'session_id', 'case_number', 'unit_id', 'created_at'])
                ->with(['unit:id,name'])
                ->latest()
                ->limit(10)
                ->get(),
            'recentSummaries' => Inertia::once(fn() => $user->clerkingSummary()
                ->whereNotNull('content')
                ->with(['clerking:id,session_id,case_number,unit_id', 'clerking.unit:id,name'])
                ->latest()
                ->limit(3)
                ->get()
                ->map(fn($summary) => [
                    'id' => $summary->id,
                    'content' => Str::markdown($summary->content),
                    'preview' => Str::limit(strip_tags(Str::markdown($summary->content)), 120),
                    'session_id' => $summary->clerking->session_id,
                    'case_number' => $summary->clerking->case_number,
                    'unit_name' => $summary->clerking->unit->name,
                    'generated_at' => $summary->generated_at,
                    'is_confirmed' => $summary->is_confirmed,
                ])),
            'totalCompleted' => $totalCompleted,
            'latestClerkings' => $latestClerkings,
        ]);
    }
}
