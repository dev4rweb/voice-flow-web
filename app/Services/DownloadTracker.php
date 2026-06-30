<?php

namespace App\Services;

use App\Data\DownloadEventData;
use App\Jobs\NotifyDownloadJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DownloadTracker
{
    public function record(DownloadEventData $event): int
    {
        $now = now();

        $id = (int) DB::table('download_events')->insertGetId([
            ...$event->toAttributes(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($this->shouldNotifyTelegram()) {
            NotifyDownloadJob::dispatch($id);
        }

        return $id;
    }

    public function find(int $id): ?object
    {
        return DB::table('download_events')->find($id);
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
            'by_site_locale' => $this->groupCount('site_locale'),
            'by_browser' => $this->groupCount('browser'),
            'by_os' => $this->groupCount('os'),
            'by_timezone' => $this->groupCount('timezone'),
            'by_country' => $this->groupCount('country_name'),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function recent(int $limit = 100): Collection
    {
        return DB::table('download_events')
            ->latest('created_at')
            ->limit($limit)
            ->get();
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

    /**
     * @return array<string, int>
     */
    private function groupCount(string $column): array
    {
        return DB::table('download_events')
            ->selectRaw($column.' as label, COUNT(*) as total')
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit(20)
            ->get()
            ->mapWithKeys(fn ($row): array => [(string) $row->label => (int) $row->total])
            ->all();
    }

    private function shouldNotifyTelegram(): bool
    {
        $telegram = config('voice_flow.telegram');

        return (bool) ($telegram['notify_downloads'] ?? false)
            && ! empty($telegram['bot_token'])
            && ! empty($telegram['chat_id']);
    }
}
