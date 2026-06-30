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

- Document root поддомена: `public/` (или корень репозитория — см. Git-деплой ниже)
- `.env`: `APP_URL=https://voice-flow.dev4rweb.com`, `DB_CONNECTION=sqlite`
- Админ: `ADMIN_EMAIL`, `ADMIN_PASSWORD`, затем `php artisan admin:ensure-user`
- Вход: `/admin/login`, dashboard: `/admin/dashboard`
- Telegram (опционально): `VOICE_FLOW_TELEGRAM_BOT_TOKEN`, `VOICE_FLOW_TELEGRAM_CHAT_ID`
- Exe загрузить вручную в `public/downloads/VoiceFlow-1.2.9.exe` или задать `VOICE_FLOW_DOWNLOAD_URL`
- После деплоя: `php artisan migrate --force`, `php artisan admin:ensure-user`, `npm run build`, `php artisan config:cache`
- Для Telegram-уведомлений нужен worker: `php artisan queue:work` (или supervisor)

## Git-деплой на Hostinger (voice-flow.dev4rweb.com)

**Приватный репозиторий менять на public не нужно** — Hostinger подключается через GitHub OAuth.

Репозиторий: `https://github.com/dev4rweb/voice-flow-web` (ветка `main`).

### 1. Подготовка папки на сервере

1. hPanel → **Files → File manager** для `voice-flow.dev4rweb.com`.
2. Создайте пустую папку, например `voice-flow-web` (рядом с `public_html`, не внутри неё).
3. Если в `public_html` уже есть файлы сайта — очистите её или смените document root (шаг 2).

### 2. Document root

**Вариант A (рекомендуется):** document root = `.../voice-flow-web/public`

Websites → `voice-flow.dev4rweb.com` → **Domains** (или настройки домена) → Document root → укажите `voice-flow-web/public`.

**Вариант B:** document root = `.../voice-flow-web` (корень репозитория). Тогда сработает корневой `.htaccess`, который перенаправляет запросы в `public/`.

### 3. Подключение Git в hPanel

1. hPanel → поиск **Git** (или **Advanced → Git**).
2. Выберите сайт `voice-flow.dev4rweb.com`.
3. **Connect GitHub** — авторизуйте аккаунт с доступом к `dev4rweb/voice-flow-web`.
4. Repository: `dev4rweb/voice-flow-web`, branch: `main`.
5. **Root directory:** `domains/voice-flow.dev4rweb.com/voice-flow-web` (путь к папке из шага 1).
6. **Build commands** (если есть поле в интерфейсе):

```bash
bash scripts/hostinger-deploy.sh
```

7. Включите **Auto-deployment** (деплой при каждом push в `main`).
8. Нажмите **Deploy** для первого деплоя.

### 4. Первичная настройка на сервере (один раз)

Через **SSH** или **Terminal** в hPanel:

```bash
cd ~/domains/voice-flow.dev4rweb.com/voice-flow-web
cp .env.example .env
# отредактируйте .env (APP_KEY, ADMIN_*, TELEGRAM_*, sqlite)
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force
php artisan admin:ensure-user
bash scripts/hostinger-deploy.sh
```

Вручную загрузите (не в Git):

- `public/downloads/VoiceFlow-1.2.9.exe`
- `storage/app/geoip/GeoLite2-City.mmdb` (если нужен GeoIP)

### 5. Webhook (если auto-deploy не срабатывает сам)

1. hPanel → Git → **Auto Deployment** → скопируйте **Webhook URL**.
2. GitHub → repo **Settings → Webhooks → Add webhook**:
   - Payload URL: URL из hPanel
   - Content type: `application/json` или `application/x-www-form-urlencoded`
   - Events: **Just the push event**
3. После push проверьте вкладку **Deployments** в hPanel.

### 6. Проверка

После push в `main`:

1. hPanel → Git → **Deployments** — статус `success`.
2. Сайт: `https://voice-flow.dev4rweb.com`
3. Админка: `https://voice-flow.dev4rweb.com/admin/login`

## GeoIP (этап 2)

1. Зарегистрируйтесь на [https://www.maxmind.com/en/geolite2/signup](https://www.maxmind.com/en/geolite2/signup)
2. В аккаунте MaxMind создайте **License Key** (My Account → Manage License Keys)
3. Скачайте **GeoLite2 City** в формате **GeoIP Database (.mmdb)**:
   - через [https://www.maxmind.com/en/accounts/current/geoip/downloads](https://www.maxmind.com/en/accounts/current/geoip/downloads)
   - или CLI: `geoipupdate` с конфигом MaxMind
4. Положите файл на сервер: `storage/app/geoip/GeoLite2-City.mmdb`
5. При необходимости укажите путь в `.env`: `VOICE_FLOW_GEOIP_DATABASE_PATH=...`

Пока `.mmdb` не подключён, работают locale браузера, timezone, OS и browser.
