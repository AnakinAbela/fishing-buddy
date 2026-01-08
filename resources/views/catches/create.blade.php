@extends('layouts.app')

@section('content')
<h1>Log New Catch</h1>

<form action="{{ route('catches.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Species</label>
        <input type="text" name="species" class="form-control" value="{{ old('species') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fishing Spot (optional)</label>
        <select name="fishing_spot_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($spots as $spot)
                <option value="{{ $spot->id }}">{{ $spot->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" class="form-control" value="{{ old('weight_kg') }}">
        </div>
        <div class="col mb-3">
            <label class="form-label">Length (cm)</label>
            <input type="number" step="0.01" name="length_cm" class="form-control" value="{{ old('length_cm') }}">
        </div>
        <div class="col mb-3">
            <label class="form-label">Depth (m)</label>
            <input type="number" step="0.01" name="depth_m" class="form-control" value="{{ old('depth_m') }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Visibility</label>
        <select name="visibility" class="form-select">
            <option value="public">Public</option>
            <option value="friends">Friends</option>
            <option value="private">Private</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Photo (optional)</label>
        <input type="file" name="photo_path" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
    </div>

    <button class="btn btn-primary">Save Catch</button>
    <a href="{{ route('catches.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection