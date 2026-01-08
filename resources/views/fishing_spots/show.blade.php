@extends('layouts.app')

@section('content')
<h1>{{ $spot->name }}</h1>

<p>{{ $spot->description ?? 'No description provided.' }}</p>
@if($spot->latitude && $spot->longitude)
    <div class="mb-3">
        <label class="form-label d-block">Pinned location</label>
        <div id="spot-map" style="height: 300px;"></div>
    </div>
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

@if($spot->latitude && $spot->longitude)
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
        const container = document.getElementById('spot-map');
        if (typeof L === 'undefined') return loadLeafletBackup(initMap);
        initMap();

        function initMap(){
            if (typeof L === 'undefined') {
                container.innerHTML = '<div class="alert alert-warning m-0">Map unavailable.</div>';
                return;
            }
            const lat = {{ $spot->latitude }};
            const lng = {{ $spot->longitude }};
            const map = L.map('spot-map').setView([lat, lng], 11);
            let fallbackApplied=false;
            if (mapKey) {
                const mt = L.tileLayer(`https://api.maptiler.com/maps/basic-v2/256/{z}/{x}/{y}.png?key=${mapKey}`, {
                    attribution:'&copy; OpenStreetMap contributors & MapTiler'
                }).addTo(map);
                mt.on('tileerror', ()=> {
                    if (fallbackApplied) return;
                    fallbackApplied=true;
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'&copy; OpenStreetMap contributors' }).addTo(map);
                });
            } else {
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'&copy; OpenStreetMap contributors' }).addTo(map);
            }
            L.marker([lat, lng]).addTo(map);
        }
    });
</script>
@endpush
@endif
@endsection
