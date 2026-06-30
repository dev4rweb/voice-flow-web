<table>
    <thead>
        <tr>
            <th>When</th>
            <th>Locale</th>
            <th>Language</th>
            <th>Client</th>
            <th>Location</th>
            <th>Timezone</th>
            <th>IP</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($events as $event)
            <tr>
                <td>{{ $event->created_at }}</td>
                <td>
                    @if ($event->site_locale)
                        <span class="pill">{{ $event->site_locale }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td>{{ $event->accept_language ?: '—' }}</td>
                <td>
                    {{ $event->os ?: '—' }}<br>
                    <span class="muted">{{ $event->browser ?: '—' }}</span>
                </td>
                <td>
                    @if ($event->city || $event->country_name)
                        {{ $event->city ?: '—' }}<br>
                        <span class="muted">{{ trim(($event->country_name ?: '').' '.($event->country_code ? '('.$event->country_code.')' : '')) }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td>{{ $event->timezone ?: '—' }}</td>
                <td>{{ $event->ip_address ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="muted">No downloads recorded yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
