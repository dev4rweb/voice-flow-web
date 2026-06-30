<?php

return [
    'name' => 'Voice Flow',
    'version' => '1.2.9',
    'domain' => env('APP_URL', 'https://voice-flow.dev4rweb.com'),
    'github_url' => 'https://github.com/dev4rweb/voice-flow',
    'logo_path' => 'images/voice-flow-icon.png',
    'favicon_path' => 'favicon.ico',
    'og_image_path' => 'images/voice-flow-icon.png',
    'download_filename' => env('VOICE_FLOW_DOWNLOAD_FILENAME', 'VoiceFlow-1.2.9.exe'),
    'download_path' => env('VOICE_FLOW_DOWNLOAD_PATH', public_path('downloads/VoiceFlow-1.2.9.exe')),
    'download_url' => env('VOICE_FLOW_DOWNLOAD_URL'),
    'download_size' => '~81 MB',
    'sha256' => '7e555b21817ee5cbf2e1f775ab7b88fa1f6b16d014eca3f0146b79747940ed90',
    'admin' => [
        'name' => env('ADMIN_NAME', 'Voice Flow Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],
    'telegram' => [
        'bot_token' => env('VOICE_FLOW_TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('VOICE_FLOW_TELEGRAM_CHAT_ID'),
        'notify_downloads' => filter_var(env('VOICE_FLOW_TELEGRAM_DOWNLOADS', true), FILTER_VALIDATE_BOOL),
    ],
    'geoip_database_path' => env('VOICE_FLOW_GEOIP_DATABASE_PATH', storage_path('app/geoip/GeoLite2-City.mmdb')),
    'default_locale' => 'en',
    'supported_locales' => [
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'dir' => 'ltr'],
        'fr' => ['name' => 'French', 'native' => 'Français', 'dir' => 'ltr'],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'dir' => 'ltr'],
        'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'dir' => 'ltr'],
        'zh' => ['name' => 'Chinese', 'native' => '中文', 'dir' => 'ltr'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl'],
    ],
];
