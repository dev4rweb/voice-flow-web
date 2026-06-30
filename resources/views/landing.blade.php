<!doctype html>
<html lang="{{ $locale }}" dir="{{ $locales[$locale]['dir'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            const storedTheme = localStorage.getItem('voice-flow-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = storedTheme || (prefersDark ? 'dark' : 'light');
        })();
    </script>
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="icon" href="{{ asset($product['favicon_path']) }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset($product['logo_path']) }}" sizes="512x512">
    <link rel="apple-touch-icon" href="{{ asset($product['logo_path']) }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    @foreach ($seo['alternates'] as $alternateLocale => $url)
        <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ $url }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $seo['xDefault'] }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:image" content="{{ $seo['ogImage'] }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <meta name="twitter:image" content="{{ $seo['ogImage'] }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="application/ld+json">@json($seo['jsonLd'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
</head>
<body class="landing-page">
    <div class="ambient" aria-hidden="true">
        <span class="orb orb-a"></span>
        <span class="orb orb-b"></span>
        <span class="orb orb-c"></span>
    </div>

    <div class="site-header-shell" data-site-header>
        <div class="site-header-bar">
            <a class="brand" href="{{ route('landing', $locale) }}">
                <img class="brand-logo" src="{{ asset($product['logo_path']) }}" width="36" height="36" alt="">
                <span class="brand-text">{{ $product['name'] }}</span>
            </a>
            <aside class="header-panel" id="site-menu-panel" data-site-menu aria-hidden="true">
                <button class="menu-close" type="button" data-menu-close aria-label="{{ $content['ui']['menu_close'] }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.05 6.343 6.343 7.05 10.793 11.5l-4.45 4.45.707.707 4.45-4.45 4.45 4.45.707-.707-4.45-4.45 4.45-4.45-.707-.707-4.45 4.45-4.45-4.45Z" fill="currentColor"/></svg>
                </button>
                <div class="header-panel-inner">
                    <nav data-site-nav aria-label="Primary navigation">
                        <a href="#features">{{ $content['nav']['features'] }}</a>
                        <a href="#how-to">{{ $content['nav']['how_to'] }}</a>
                        <a href="#requirements">{{ $content['nav']['requirements'] }}</a>
                        <a href="#privacy">{{ $content['nav']['privacy'] }}</a>
                        <a class="nav-cta" href="{{ route('download.file') }}">{{ $content['nav']['download'] }}</a>
                    </nav>
                </div>
            </aside>
            <div class="header-bar-controls">
                <div class="toolbar" aria-label="{{ $content['ui']['theme_label'] }} / {{ $content['ui']['language_label'] }}">
                    <label class="select-label">
                        <span>{{ $content['ui']['language_label'] }}</span>
                        <select data-locale-switcher>
                            @foreach ($locales as $code => $language)
                                <option value="{{ route('landing', $code) }}" @selected($code === $locale)>{{ $language['native'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="theme-toggle" type="button" data-theme-toggle aria-label="{{ $content['ui']['theme_toggle'] }}">
                        <span data-theme-text data-light-label="{{ $content['ui']['theme_light'] }}" data-dark-label="{{ $content['ui']['theme_dark'] }}">{{ $content['ui']['theme_light'] }}</span>
                    </button>
                </div>
                <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="site-menu-panel" aria-label="{{ $content['ui']['menu_toggle'] }}">
                    <span class="nav-toggle-lines"></span>
                </button>
            </div>
        </div>
    </div>

    <button class="menu-backdrop" type="button" data-menu-backdrop aria-hidden="true" tabindex="-1"></button>

    <main>
        <section class="hero">
            <div data-reveal>
                <p class="eyebrow">{{ $content['hero']['eyebrow'] }}</p>
                <h1>{{ $content['hero']['title'] }}</h1>
                <p class="lead">{{ $content['hero']['body'] }}</p>
                <div class="actions">
                    <a class="button button-primary" href="{{ route('download.file') }}">{{ $content['hero']['primary_cta'] }}</a>
                    {{-- <a class="button button-secondary" href="{{ $product['github_url'] }}">{{ $content['hero']['secondary_cta'] }}</a> --}}
                </div>
                <ul class="badges">
                    @foreach ($content['badges'] as $index => $badge)
                        <li data-reveal data-reveal-delay="{{ min($index + 1, 4) }}">{{ $badge }}</li>
                    @endforeach
                </ul>
            </div>
            <aside class="card hero-card" data-reveal data-reveal-delay="2">
                <h2>{{ $content['download_title'] }}</h2>
                <dl>
                    <div><dt>{{ $content['download_details']['size'] }}</dt><dd>{{ $product['download_size'] }}</dd></div>
                    <div><dt>{{ $content['download_details']['system'] }}</dt><dd>Windows 10 / 11, 64-bit</dd></div>
                    <div><dt>{{ $content['download_details']['sha'] }}</dt><dd><code>{{ $product['sha256'] }}</code></dd></div>
                    <div><dt>{{ $content['download_details']['license'] }}</dt><dd>MIT</dd></div>
                </dl>
                <p>{{ $content['download_note'] }}</p>
            </aside>
        </section>

        <section class="section split">
            <div data-reveal>
                <h2>{{ $content['about_title'] }}</h2>
                <p>{{ $content['about_body'] }}</p>
            </div>
            <div class="notice" data-reveal data-reveal-delay="2">
                <strong>{{ $product['name'] }} {{ $product['version'] }}</strong>
                <p>{{ $content['supported_languages'] }}</p>
            </div>
        </section>

        <section class="section" id="features">
            <div class="section-head" data-reveal>
                <h2>{{ $content['features_title'] }}</h2>
            </div>
            <div class="grid">
                @foreach ($content['features'] as $index => $feature)
                    <article class="card" data-reveal data-reveal-delay="{{ min(($index % 3) + 1, 4) }}">
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section split" id="how-to">
            <div data-reveal>
                <h2>{{ $content['how_to_title'] }}</h2>
                <ol>@foreach ($content['steps'] as $step)<li>{{ $step }}</li>@endforeach</ol>
            </div>
            <div class="warning" data-reveal data-reveal-delay="2">
                <h2>{{ $content['smartscreen_title'] }}</h2>
                <p>{{ $content['smartscreen_body'] }}</p>
            </div>
        </section>

        <section class="section split" id="requirements">
            <div data-reveal>
                <h2>{{ $content['requirements_title'] }}</h2>
                <dl>@foreach ($content['requirements'] as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
            </div>
            <div id="privacy" class="card" data-reveal data-reveal-delay="2">
                <h2>{{ $content['privacy_title'] }}</h2>
                <ul>@foreach ($content['privacy'] as $item)<li>{{ $item }}</li>@endforeach</ul>
            </div>
        </section>

        <section class="section" id="recognition-languages">
            <div class="section-head" data-reveal>
                <h2>{{ $content['recognition_languages_title'] }}</h2>
                <p>{{ $content['recognition_languages_body'] }}</p>
            </div>
            <details class="language-list-card card" data-reveal data-reveal-delay="2">
                <summary>{{ $content['recognition_languages_toggle'] }} ({{ $recognitionLanguageCount }})</summary>
                <p class="language-list-note">{{ $content['recognition_languages_note'] }}</p>
                <ul class="language-grid">
                    @foreach ($recognitionLanguages as $languageName)
                        <li>{{ $languageName }}</li>
                    @endforeach
                </ul>
            </details>
        </section>

        <section class="section">
            <div class="section-head" data-reveal>
                <h2>{{ $content['faq_title'] }}</h2>
            </div>
            <div class="faq">
                @foreach ($content['faq'] as $index => $item)
                    <details data-reveal data-reveal-delay="{{ min($index + 1, 4) }}">
                        <summary>{{ $item['question'] }}</summary>
                        <p>{{ $item['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="site-footer" data-reveal>
        <p>{{ $content['footer'] }}</p>
        <div class="locale-switcher">@foreach ($locales as $code => $language)<a @class(['active' => $code === $locale]) href="{{ route('landing', $code) }}">{{ $language['native'] }}</a>@endforeach</div>
    </footer>

    <button class="scroll-top" type="button" data-scroll-top aria-label="{{ $content['ui']['scroll_top'] }}" hidden>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5.5 6 11.5l1.4 1.4 3.6-3.6V18h2V9.3l3.6 3.6 1.4-1.4-6-6Z" fill="currentColor"/></svg>
    </button>
</body>
</html>
