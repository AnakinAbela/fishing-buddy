@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $user->name }}</h1>

    @if(auth()->id() === $user->id)
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">Edit Profile</a>
    @endif
</div>

<p class="text-muted mb-2">{{ $user->bio ?? 'No bio yet.' }}</p>

<div class="d-flex gap-3 mb-4">
    <div><strong>{{ $user->followers->count() }}</strong> followers</div>
    <div><strong>{{ $user->following->count() }}</strong> following</div>
</div>

@if($user->catches->count())
    <h4 class="mb-3">Catches</h4>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        @foreach($user->catches as $catch)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $catch->species }}</h5>
                        <p class="mb-1 text-muted small">
                            {{ $catch->created_at->format('d M Y') }}
                            @if($catch->fishingSpot)
                                • {{ $catch->fishingSpot->name }}
                            @endif
                        </p>
                        <a href="{{ route('catches.show', $catch) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted">No catches yet.</p>
@endif
@endsection
