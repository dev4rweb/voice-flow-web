<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DownloadTracker;
use Illuminate\View\View;

final class DownloadsController extends Controller
{
    public function index(DownloadTracker $tracker): View
    {
        return view('admin.downloads', [
            'stats' => $tracker->stats(),
            'events' => $tracker->recent(100),
        ]);
    }
}
