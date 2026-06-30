<?php

namespace App\Console\Commands;

use App\Services\DownloadTracker;
use Illuminate\Console\Command;

final class DownloadStatsCommand extends Command
{
    protected $signature = 'downloads:stats';

    protected $description = 'Show Voice Flow download statistics.';

    public function handle(DownloadTracker $tracker): int
    {
        foreach (explode(PHP_EOL, $tracker->formatForConsole()) as $line) {
            $this->line($line);
        }

        return self::SUCCESS;
    }
}
