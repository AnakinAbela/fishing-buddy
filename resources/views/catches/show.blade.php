@extends('layouts.app')

@section('content')
<h1>{{ $catch->species }}</h1>

{{-- Catch Photo --}}
@if($catch->photo_path)
    <img src="{{ asset('storage/' . $catch->photo_path) }}" class="img-fluid mb-3 rounded">
@endif

{{-- Catch Details --}}
<ul class="list-group mb-3">
    <li class="list-group-item">
        <strong>Weight:</strong> {{ $catch->weight_kg ?? 'N/A' }} kg
    </li>
    <li class="list-group-item">
        <strong>Length:</strong> {{ $catch->length_cm ?? 'N/A' }} cm
    </li>
    <li class="list-group-item">
        <strong>Depth:</strong> {{ $catch->depth_m ?? 'N/A' }} m
    </li>
    <li class="list-group-item">
        <strong>Visibility:</strong> {{ ucfirst($catch->visibility) }}
    </li>
    @if($catch->fishingSpot)
        <li class="list-group-item">
            <strong>Fishing Spot:</strong> {{ $catch->fishingSpot->name }}
        </li>
    @endif
</ul>

{{-- Notes --}}
@if($catch->notes)
    <p><strong>Notes:</strong><br>{{ $catch->notes }}</p>
@endif

<p class="text-muted">
    Logged by {{ $catch->user->name }}
    on {{ $catch->created_at->format('d M Y') }}
</p>

@php
    $author = $catch->user;
    $isOwner = auth()->check() && auth()->id() === $author->id;
    $followerCount = $author->followers->count();
    $userFollowsAuthor = auth()->check() && $author->followers->contains('id', auth()->id());
@endphp

<div class="d-flex align-items-center flex-wrap gap-2 mb-3">
    <span class="text-muted small">
        {{ $followerCount }} {{ $followerCount === 1 ? 'follower' : 'followers' }}
    </span>

    @auth
        @if(!$isOwner)
            <form action="{{ route('follow.toggle', $author) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm {{ $userFollowsAuthor ? 'btn-success' : 'btn-outline-success' }}">
                    {{ $userFollowsAuthor ? 'Unfollow' : 'Follow' }}
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success">Login to follow</a>
    @endauth
</div>

<div class="mb-4">
    @can('update', $catch)
        <a href="{{ route('catches.edit', $catch) }}" class="btn btn-secondary">Edit</a>
    @endcan
    <a href="{{ route('catches.index') }}" class="btn btn-primary">Back to Catches</a>
</div>

<hr>

@php
    $likeCount = $catch->likes->count();
    $userHasLiked = auth()->check() && $catch->likes->contains('user_id', auth()->id());
@endphp

<div class="d-flex align-items-center gap-2 mb-4">
    @auth
        <form action="{{ route('likes.toggle', $catch) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm {{ $userHasLiked ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $userHasLiked ? 'Unlike' : 'Like' }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to like</a>
    @endauth

    <span class="text-muted small">
        {{ $likeCount }} {{ $likeCount === 1 ? 'like' : 'likes' }}
    </span>
</div>

{{-- Location map --}}
@if($catch->fishingSpot && $catch->fishingSpot->latitude && $catch->fishingSpot->longitude)
    <div class="mb-4">
        <label class="form-label d-block">Location</label>
        <div id="catch-map" style="height: 300px;"></div>
    </div>
@endif

<h4>Comments</h4>

{{-- Existing Comments --}}
@if($catch->comments->count())
    <ul class="list-group mb-3">
        @foreach($catch->comments as $comment)
            <li class="list-group-item">
                <strong>{{ $comment->user->name }}</strong>
                <span class="text-muted small">
                    • {{ $comment->created_at->diffForHumans() }}
                </span>

                <p class="mb-1 mt-1">{{ $comment->content }}</p>

                @auth
                    @if(auth()->id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-link text-danger p-0">
                                Delete
                            </button>
                        </form>
                    @endif
                @endauth
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted">No comments yet.</p>
@endif

{{-- Add Comment --}}
@auth
<form action="{{ route('comments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="catch_log_id" value="{{ $catch->id }}">

    <div class="mb-3">
        <label class="form-label">Add a comment</label>
        <textarea name="content" class="form-control" rows="2" required></textarea>
    </div>

    <button class="btn btn-primary btn-sm">Post Comment</button>
</form>
@else
<p>
    <a href="{{ route('login') }}">Login</a> to comment.
</p>
@endauth

@endsection

@if($catch->fishingSpot && $catch->fishingSpot->latitude && $catch->fishingSpot->longitude)
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .leaflet-container { position: relative; outline: none; }
    .leaflet-pane, .leaflet-tile, .leaflet-marker-icon, .leaflet-marker-shadow, .leaflet-tile-container, .leaflet-pane > svg, .leaflet-pane > canvas { position: absolute; left: 0; top: 0; }
    .leaflet-container img { max-width: none !important; }
    .leaflet-tile { width: 256px; height: 256px; }
</style>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" defer></script>
<script>
    function loadLeafletBackup(cb){
        const s=document.createElement('script');
        s.src='https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        s.onload=cb; s.onerror=cb; document.head.appendChild(s);
    }
    document.addEventListener('DOMContentLoaded', () => {
        const mapKey = "{{ env('MAPTILER_KEY') ?? config('services.maptiler.key') }}";
        const container = document.getElementById('catch-map');

        function initMap(){
            if (typeof L === 'undefined') {
                container.innerHTML = '<div class="alert alert-warning m-0">Map unavailable.</div>';
                return;
            }
            const lat = {{ $catch->fishingSpot->latitude }};
            const lng = {{ $catch->fishingSpot->longitude }};
            const map = L.map('catch-map').setView([lat, lng], 12);
            let fallback=false;
            if (mapKey) {
                const mt = L.tileLayer(`https://api.maptiler.com/maps/basic-v2/256/{z}/{x}/{y}.png?key=${mapKey}`, {
                    attribution:'&copy; OpenStreetMap contributors & MapTiler'
                }).addTo(map);
                mt.on('tileerror', ()=> {
                    if (fallback) return;
                    fallback=true;
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'&copy; OpenStreetMap contributors' }).addTo(map);
                });
            } else {
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'&copy; OpenStreetMap contributors' }).addTo(map);
            }
            L.marker([lat, lng]).addTo(map);
        }

        if (typeof L === 'undefined') {
            loadLeafletBackup(initMap);
        } else {
            initMap();
        }
    });
</script>
@endpush
@endif
