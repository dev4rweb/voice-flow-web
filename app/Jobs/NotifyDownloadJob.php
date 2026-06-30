<?php

namespace App\Jobs;

use App\Services\DownloadTracker;
use App\Services\TelegramDownloadNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class NotifyDownloadJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $eventId) {}

    public function handle(DownloadTracker $tracker, TelegramDownloadNotifier $notifier): void
    {
        $event = $tracker->find($this->eventId);

        if ($event === null) {
            return;
        }

        $notifier->notify($event, $tracker->stats()['total']);
    }
}
