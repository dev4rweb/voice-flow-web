@extends('layouts.admin')

@section('title', 'Downloads')
@section('heading', 'Downloads')

@section('content')
    <p class="muted">Full download analytics with local GeoLite2 lookup when the database file is present.</p>

    @include('admin.partials.stats-cards')

    <div class="lists">
        @include('admin.partials.stats-lists')
    </div>

    <div class="section-head">
        <h2>Recent events</h2>
    </div>

    @include('admin.partials.events-table', ['events' => $events])
@endsection
