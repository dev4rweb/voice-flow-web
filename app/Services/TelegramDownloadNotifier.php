<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class TelegramDownloadNotifier
{
    public function notify(object $event, int $totalDownloads): bool
    {
        $telegram = config('voice_flow.telegram');
        $token = (string) ($telegram['bot_token'] ?? '');
        $chatId = (string) ($telegram['chat_id'] ?? '');

        if ($token === '' || $chatId === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $this->buildMessage($event, $totalDownloads),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if (! $response->successful()) {
                Log::warning('Telegram download notification failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Telegram download notification failed.', [
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function buildMessage(object $event, int $totalDownloads): string
    {
        $lines = [
            '📥 <b>Voice Flow download</b>',
            '<b>File:</b> '.e((string) $event->filename),
            '<b>Page locale:</b> '.e($event->site_locale ?: 'unknown'),
            '<b>Browser language:</b> '.e($this->shorten((string) ($event->accept_language ?: 'unknown'), 120)),
            '<b>OS:</b> '.e($event->os ?: 'unknown'),
            '<b>Browser:</b> '.e($event->browser ?: 'unknown'),
            '<b>Timezone:</b> '.e($event->timezone ?: 'unknown'),
            '<b>Location:</b> '.e($this->formatLocation($event)),
            '<b>IP:</b> '.e($event->ip_address ?: 'unknown'),
            '<b>Time:</b> '.e((string) $event->created_at),
            '<b>Total downloads:</b> '.$totalDownloads,
        ];

        if (! empty($event->referer)) {
            $lines[] = '<b>Referer:</b> '.e($this->shorten((string) $event->referer, 180));
        }

        return implode("\n", $lines);
    }

    private function shorten(string $value, int $limit): string
    {
        return mb_strlen($value) > $limit ? mb_substr($value, 0, $limit - 1).'…' : $value;
    }

    private function formatLocation(object $event): string
    {
        $parts = array_filter([
            $event->city ?? null,
            $event->country_name ?? null,
            isset($event->country_code) && $event->country_code !== '' ? '('.$event->country_code.')' : null,
        ]);

        return $parts === [] ? 'unknown' : implode(', ', $parts);
    }
}
