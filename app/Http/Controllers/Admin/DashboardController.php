<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DownloadTracker;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(DownloadTracker $tracker): View
    {
        return view('admin.dashboard', [
            'stats' => $tracker->stats(),
            'events' => $tracker->recent(10),
        ]);
    }
}
