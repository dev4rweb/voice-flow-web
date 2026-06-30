<div class="grid">
    <div class="card">
        <strong>{{ number_format($stats['total']) }}</strong>
        <span>Total downloads</span>
    </div>
    <div class="card">
        <strong>{{ $stats['last_download_at'] ?? 'never' }}</strong>
        <span>Last download</span>
    </div>
    <div class="card">
        <strong>{{ count($stats['by_day']) }}</strong>
        <span>Days tracked</span>
    </div>
</div>
