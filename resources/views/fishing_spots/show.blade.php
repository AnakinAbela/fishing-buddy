@extends('layouts.app')

@section('content')
<h1>{{ $spot->name }}</h1>

<p>{{ $spot->description ?? 'No description provided.' }}</p>
@if($spot->country || $spot->city)
    <p><strong>Country:</strong> {{ $spot->country ?? 'N/A' }}</p>
    <p><strong>Town/City:</strong> {{ $spot->city ?? 'N/A' }}</p>
@else
    <p><strong>Location:</strong> Lat {{ $spot->latitude }}, Lng {{ $spot->longitude }}</p>
@endif
<p><small class="text-muted">Added by {{ $spot->user->name }} on {{ $spot->created_at->format('d M Y') }}</small></p>

@can('update', $spot)
    <a href="{{ route('spots.edit', $spot) }}" class="btn btn-secondary">Edit</a>
@endcan
@can('delete', $spot)
    <form action="{{ route('spots.destroy', $spot) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this spot?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Delete</button>
    </form>
@endcan
<a href="{{ route('spots.index') }}" class="btn btn-primary">Back to Spots</a>
@endsection
