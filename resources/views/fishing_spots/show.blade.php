@extends('layouts.app')

@section('content')
<h1>{{ $spot->name }}</h1>

<p>{{ $spot->description ?? 'No description provided.' }}</p>
<p><strong>Latitude:</strong> {{ $spot->latitude }}</p>
<p><strong>Longitude:</strong> {{ $spot->longitude }}</p>
<p><small class="text-muted">Added by {{ $spot->user->name }} on {{ $spot->created_at->format('d M Y') }}</small></p>

<a href="{{ route('spots.edit', $spot) }}" class="btn btn-secondary">Edit</a>
<form action="{{ route('spots.destroy', $spot) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this spot?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger">Delete</button>
</form>
<a href="{{ route('spots.index') }}" class="btn btn-primary">Back to Spots</a>
@endsection