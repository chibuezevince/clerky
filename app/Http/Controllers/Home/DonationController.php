<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonationController extends Controller
{
    function index() {
        return Inertia::render('Donate/Index',);
    }
}
