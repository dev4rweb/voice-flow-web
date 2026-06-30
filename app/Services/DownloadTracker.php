<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

final class DownloadTracker
{
    public function record(string $filename, ?string $ipAddress, ?string $userAgent): void
    {
        DB::table('download_events')->insert([
            'filename' => $filename,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent ? mb_substr($userAgent, 0, 500) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function stats(): array
    {
        $last = DB::table('download_events')->latest('created_at')->first();
        $byDay = DB::table('download_events')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderByDesc('day')
            ->limit(30)
            ->get()
            ->mapWithKeys(fn ($row): array => [$row->day => (int) $row->total])
            ->all();

        return [
            'total' => (int) DB::table('download_events')->count(),
            'last_download_at' => $last?->created_at,
            'by_day' => $byDay,
        ];
    }

    public function formatForConsole(): string
    {
        $stats = $this->stats();
        $lines = ['Voice Flow downloads', 'Total: '.$stats['total'], 'Last: '.($stats['last_download_at'] ?? 'never')];

        foreach ($stats['by_day'] as $day => $total) {
            $lines[] = "{$day}: {$total}";
        }

        return implode(PHP_EOL, $lines);
    }
}
