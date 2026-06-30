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
- `.env`: `APP_URL=https://voice-flow.dev4rweb.com`, `DB_CONNECTION=sqlite`
- Админ: `ADMIN_EMAIL`, `ADMIN_PASSWORD`, затем `php artisan admin:ensure-user`
- Вход: `/admin/login`, dashboard: `/admin/dashboard`
- Telegram (опционально): `VOICE_FLOW_TELEGRAM_BOT_TOKEN`, `VOICE_FLOW_TELEGRAM_CHAT_ID`
- Exe загрузить вручную в `public/downloads/VoiceFlow-1.2.9.exe` или задать `VOICE_FLOW_DOWNLOAD_URL`
- После деплоя: `php artisan migrate --force`, `php artisan admin:ensure-user`, `npm run build`, `php artisan config:cache`
- Для Telegram-уведомлений нужен worker: `php artisan queue:work` (или supervisor)

## GeoIP (этап 2)

1. Зарегистрируйтесь на [https://www.maxmind.com/en/geolite2/signup](https://www.maxmind.com/en/geolite2/signup)
2. В аккаунте MaxMind создайте **License Key** (My Account → Manage License Keys)
3. Скачайте **GeoLite2 City** в формате **GeoIP Database (.mmdb)**:
   - через [https://www.maxmind.com/en/accounts/current/geoip/downloads](https://www.maxmind.com/en/accounts/current/geoip/downloads)
   - или CLI: `geoipupdate` с конфигом MaxMind
4. Положите файл на сервер: `storage/app/geoip/GeoLite2-City.mmdb`
5. При необходимости укажите путь в `.env`: `VOICE_FLOW_GEOIP_DATABASE_PATH=...`

Пока `.mmdb` не подключён, работают locale браузера, timezone, OS и browser.
