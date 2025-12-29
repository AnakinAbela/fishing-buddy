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
        <label class="form-label">Pin location</label>
        <div id="map" style="height: 300px;"></div>
        <div class="form-text">Click the map to drop a pin. Lat/Long will fill below.</div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" required readonly>
        </div>
        <div class="col-md-6">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" required readonly>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Create Spot</button>
    <a href="{{ route('spots.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC0=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j7kGStlL1r58u1G3S1tqkYOC3kP2JbKyNG2IeC4=" crossorigin=""></script>
<script>
    const map = L.map('map').setView([35.9375, 14.3754], 8); // Default: Malta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    function setMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
    }

    map.on('click', (e) => {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // If old values exist, drop a marker there
    if (latInput.value && lngInput.value) {
        setMarker(parseFloat(latInput.value), parseFloat(lngInput.value));
        map.setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 12);
    }
</script>
@endpush
@endsection
