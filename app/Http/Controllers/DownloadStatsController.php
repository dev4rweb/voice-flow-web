<?php

namespace App\Http\Controllers;

use App\Services\DownloadTracker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DownloadStatsController extends Controller
{
    public function show(Request $request, DownloadTracker $tracker): JsonResponse
    {
        $configuredToken = (string) config('voice_flow.stats_token');
        $providedToken = (string) $request->query('token');

        abort_if($configuredToken === '' || ! hash_equals($configuredToken, $providedToken), 403);

        return response()->json($tracker->stats());
    }
}
