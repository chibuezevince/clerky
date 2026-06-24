<?php

use App\Models\Clerking;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('clerking.{sessionId}', function (User $user, string $sessionId) {
    return $user->id === (int) Clerking::where('session_id', $sessionId)->first()->user_id;
});
