@extends('layouts.app')

@section('content')
<h1>Edit Catch</h1>

<form action="{{ route('catches.update', $catch) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Species</label>
        <input type="text" name="species" class="form-control"
               value="{{ old('species', $catch->species) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fishing Spot</label>
        <select name="fishing_spot_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($spots as $spot)
                <option value="{{ $spot->id }}"
                    @selected($catch->fishing_spot_id == $spot->id)>
                    {{ $spot->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg"
                   value="{{ old('weight_kg', $catch->weight_kg) }}" class="form-control">
        </div>
        <div class="col mb-3">
            <label class="form-label">Length (cm)</label>
            <input type="number" step="0.01" name="length_cm"
                   value="{{ old('length_cm', $catch->length_cm) }}" class="form-control">
        </div>
        <div class="col mb-3">
            <label class="form-label">Depth (m)</label>
            <input type="number" step="0.01" name="depth_m"
                   value="{{ old('depth_m', $catch->depth_m) }}" class="form-control">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Visibility</label>
        <select name="visibility" class="form-select">
            @foreach(['public', 'friends', 'private'] as $option)
                <option value="{{ $option }}" @selected($catch->visibility === $option)>
                    {{ ucfirst($option) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Replace Photo</label>
        <input type="file" name="photo_path" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes', $catch->notes) }}</textarea>
    </div>

    <button class="btn btn-primary">Update Catch</button>
    <a href="{{ route('catches.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection