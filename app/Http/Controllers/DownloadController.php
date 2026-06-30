<?php

namespace App\Http\Controllers;

use App\Services\DownloadTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DownloadController extends Controller
{
    public function file(Request $request, DownloadTracker $tracker): BinaryFileResponse|RedirectResponse
    {
        $filename = (string) config('voice_flow.download_filename');
        $path = $this->absolutePath((string) config('voice_flow.download_path'));
        $externalUrl = config('voice_flow.download_url');

        abort_if(empty($externalUrl) && ! is_file($path), 404, 'Voice Flow installer is not uploaded yet.');

        $tracker->record($filename, $request->ip(), $request->userAgent());

        if ($externalUrl) {
            return redirect()->away($externalUrl);
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.microsoft.portable-executable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function absolutePath(string $path): string
    {
        if (preg_match('/^([A-Za-z]:[\\\\\/]|\/)/', $path) === 1) {
            return $path;
        }

        return base_path($path);
    }
}
