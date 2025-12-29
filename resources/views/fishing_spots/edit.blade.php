@extends('layouts.app')

@section('content')
<h1>Edit Fishing Spot</h1>

<form action="{{ route('spots.update', $spot) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Spot Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $spot->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $spot->country) }}">
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Town/City</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $spot->city) }}">
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description (optional)</label>
        <textarea class="form-control" id="description" name="description">{{ old('description', $spot->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Pin location</label>
        <div id="map" style="height: 300px;"></div>
        <div class="form-text">Click the map to move the pin. Lat/Long will fill below.</div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $spot->latitude) }}" required readonly>
        </div>
        <div class="col-md-6">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $spot->longitude) }}" required readonly>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Spot</button>
    <a href="{{ route('spots.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC0=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j7kGStlL1r58u1G3S1tqkYOC3kP2JbKyNG2IeC4=" crossorigin=""></script>
<script>
    const map = L.map('map').setView([{{ old('latitude', $spot->latitude ?? 35.9375) }}, {{ old('longitude', $spot->longitude ?? 14.3754) }}], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([{{ old('latitude', $spot->latitude ?? 35.9375) }}, {{ old('longitude', $spot->longitude ?? 14.3754) }}]).addTo(map);
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    function setMarker(lat, lng) {
        marker.setLatLng([lat, lng]);
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
    }

    map.on('click', (e) => {
        setMarker(e.latlng.lat, e.latlng.lng);
    });
</script>
@endpush
@endsection
