<?php

namespace App\Services;

final class RecognitionLanguageCatalog
{
    /** @var list<string>|null */
    private static ?array $names = null;

    /** @return list<string> */
    public function names(): array
    {
        if (self::$names !== null) {
            return self::$names;
        }

        $path = dirname(__DIR__, 2).'/resources/data/recognition_languages.json';
        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Recognition language catalog is invalid.');
        }

        self::$names = array_values($decoded);

        return self::$names;
    }

    public function count(): int
    {
        return count($this->names());
    }
}
