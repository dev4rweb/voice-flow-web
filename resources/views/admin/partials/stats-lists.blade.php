<section class="card">
    <h2>By page locale</h2>
    @if ($stats['by_site_locale'] === [])
        <p class="muted">No data yet.</p>
    @else
        <ul>
            @foreach ($stats['by_site_locale'] as $locale => $total)
                <li><span class="pill">{{ $locale }}</span> {{ $total }}</li>
            @endforeach
        </ul>
    @endif
</section>

<section class="card">
    <h2>By country</h2>
    @if ($stats['by_country'] === [])
        <p class="muted">No data yet.</p>
    @else
        <ul>
            @foreach ($stats['by_country'] as $country => $total)
                <li>{{ $country }} — {{ $total }}</li>
            @endforeach
        </ul>
    @endif
</section>

<section class="card">
    <h2>By timezone</h2>
    @if ($stats['by_timezone'] === [])
        <p class="muted">No data yet.</p>
    @else
        <ul>
            @foreach ($stats['by_timezone'] as $timezone => $total)
                <li>{{ $timezone }} — {{ $total }}</li>
            @endforeach
        </ul>
    @endif
</section>

<section class="card">
    <h2>By OS</h2>
    @if ($stats['by_os'] === [])
        <p class="muted">No data yet.</p>
    @else
        <ul>
            @foreach ($stats['by_os'] as $os => $total)
                <li>{{ $os }} — {{ $total }}</li>
            @endforeach
        </ul>
    @endif
</section>

<section class="card">
    <h2>By browser</h2>
    @if ($stats['by_browser'] === [])
        <p class="muted">No data yet.</p>
    @else
        <ul>
            @foreach ($stats['by_browser'] as $browser => $total)
                <li>{{ $browser }} — {{ $total }}</li>
            @endforeach
        </ul>
    @endif
</section>
