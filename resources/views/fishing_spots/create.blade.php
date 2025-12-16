@extends('layouts.app')

@section('content')
<h1>Add New Fishing Spot</h1>

<form action="{{ route('spots.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Spot Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description (optional)</label>
        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="latitude" class="form-label">Latitude</label>
        <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
    </div>

    <div class="mb-3">
        <label for="longitude" class="form-label">Longitude</label>
        <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Create Spot</button>
    <a href="{{ route('spots.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection