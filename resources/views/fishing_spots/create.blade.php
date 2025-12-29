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
        <label for="country" class="form-label">Country</label>
        <select class="form-select" id="country" name="country" required></select>
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Town/City</label>
        <select class="form-select" id="city" name="city" required></select>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description (optional)</label>
        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Create Spot</button>
    <a href="{{ route('spots.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@push('scripts')
<script>
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');

    async function loadCountries() {
        try {
            const res = await fetch('https://restcountries.com/v3.1/all');
            const data = await res.json();
            const sorted = data.sort((a,b) => a.name.common.localeCompare(b.name.common));
            countrySelect.innerHTML = '<option value=\"\">Select a country</option>' + sorted.map(c => `<option value=\"${c.name.common}\">${c.name.common}</option>`).join('');
        } catch (e) {
            countrySelect.innerHTML = '<option value=\"\">Unable to load countries</option>';
        }
    }

    async function loadCities(country) {
        citySelect.innerHTML = '<option value=\"\">Loading...</option>';
        try {
            const res = await fetch('https://countriesnow.space/api/v0.1/countries/cities', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ country })
            });
            const data = await res.json();
            if (data && data.data && Array.isArray(data.data)) {
                citySelect.innerHTML = data.data.map(city => `<option value=\"${city}\">${city}</option>`).join('');
            } else {
                citySelect.innerHTML = '<option value=\"\">No cities found</option>';
            }
        } catch (e) {
            citySelect.innerHTML = '<option value=\"\">Unable to load cities</option>';
        }
    }

    countrySelect.addEventListener('change', (e) => {
        const country = e.target.value;
        if (country) loadCities(country);
        else citySelect.innerHTML = '<option value=\"\">Select a country first</option>';
    });

    loadCountries();
</script>
@endpush
@endsection
