<?php

namespace App\Services;

final class UserAgentParser
{
    /**
     * @return array{browser: ?string, os: ?string}
     */
    public function parse(?string $userAgent): array
    {
        if ($userAgent === null || trim($userAgent) === '') {
            return ['browser' => null, 'os' => null];
        }

        return [
            'browser' => $this->detectBrowser($userAgent),
            'os' => $this->detectOs($userAgent),
        ];
    }

    private function detectBrowser(string $userAgent): ?string
    {
        $patterns = [
            'Edge' => '/Edg(?:A|IOS)?\/([\d.]+)/',
            'Opera' => '/OPR\/([\d.]+)/',
            'Chrome' => '/Chrome\/([\d.]+)/',
            'Firefox' => '/Firefox\/([\d.]+)/',
            'Safari' => '/Version\/([\d.]+).*Safari/',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $matches) === 1) {
                if ($name === 'Chrome' && str_contains($userAgent, 'Edg/')) {
                    continue;
                }

                return $name.' '.$matches[1];
            }
        }

        return null;
    }

    private function detectOs(string $userAgent): ?string
    {
        if (preg_match('/Windows NT 10\.0/u', $userAgent) === 1) {
            return 'Windows 10/11';
        }

        if (preg_match('/Windows NT ([\d.]+)/u', $userAgent, $matches) === 1) {
            return 'Windows NT '.$matches[1];
        }

        if (preg_match('/Mac OS X ([\d_]+)/u', $userAgent, $matches) === 1) {
            return 'macOS '.str_replace('_', '.', $matches[1]);
        }

        if (preg_match('/Android ([\d.]+)/u', $userAgent, $matches) === 1) {
            return 'Android '.$matches[1];
        }

        if (preg_match('/iPhone OS ([\d_]+)/u', $userAgent, $matches) === 1) {
            return 'iOS '.str_replace('_', '.', $matches[1]);
        }

        if (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        }

        return null;
    }
}
