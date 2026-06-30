@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <p class="muted">Overview of landing downloads. Release management will appear here later.</p>

    @include('admin.partials.stats-cards')

    <div class="lists">
        @include('admin.partials.stats-lists')
    </div>

    <div class="section-head">
        <h2>Recent downloads</h2>
        <a class="button" href="{{ route('admin.downloads') }}">View all</a>
    </div>

    @include('admin.partials.events-table', ['events' => $events])
@endsection
