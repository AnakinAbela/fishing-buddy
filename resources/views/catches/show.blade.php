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
    <a href="{{ route('catches.edit', $catch) }}" class="btn btn-secondary">Edit</a>
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

{{-- Weather / Conditions --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-2">Weather snapshot</h5>
        @if($catch->fishingSpot)
            <p class="mb-1 text-muted small">
                Spot: {{ $catch->fishingSpot->name }}
                ({{ number_format($catch->fishingSpot->latitude, 2) }}, {{ number_format($catch->fishingSpot->longitude, 2) }})
            </p>
        @endif

        @if(!empty($weather))
            <p class="mb-1">Wind: {{ $weather['wind_speed'] ?? 'N/A' }} m/s
                @if(!empty($weather['wind_direction']))
                    ({{ round($weather['wind_direction']) }}°)
                @endif
            </p>
            <p class="mb-1">Gusts: {{ $weather['wind_gusts'] ?? 'N/A' }} m/s</p>
            <p class="mb-0">Temp: {{ $weather['temperature'] ?? 'N/A' }} °C</p>
        @else
            <p class="mb-1">Live weather unavailable right now.</p>
            <p class="mb-0 text-muted small">Check again soon to see wind and temperature for this spot.</p>
        @endif
    </div>
</div>

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
