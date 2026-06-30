# voice-flow-web

Лендинг продукта Voice Flow для `https://voice-flow.dev4rweb.com`.

## Локальный запуск

```powershell
$env:Path = "D:\OSP\modules\PHP-8.2\PHP\" + ";" + $env:Path
composer install
npm install
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

## Деплой

- Document root поддомена: `public/`
- `.env`: `APP_URL=https://voice-flow.dev4rweb.com`, `DB_CONNECTION=sqlite`, `VOICE_FLOW_STATS_TOKEN=...`
- Exe загрузить вручную в `public/downloads/VoiceFlow-1.2.9.exe` или задать `VOICE_FLOW_DOWNLOAD_URL`
- После деплоя: `php artisan migrate --force`, `npm run build`, `php artisan config:cache`
