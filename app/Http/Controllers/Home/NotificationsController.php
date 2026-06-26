<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class NotificationsController extends Controller {
    function markAsRead(string $id) {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return back();
    }

    function markAllAsRead() {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    }

    function destroy(string $id) {
        auth()->user()->notifications()->where('id', $id)->delete();
        return back();
    }

    function destroyAll() {
        auth()->user()->notifications()->delete();
        return back();
    }
}
