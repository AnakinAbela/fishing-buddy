<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidMapLocation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = config('services.maptiler.key');
        if (! $key) {
            $fail('Location validation unavailable. Please configure the map API key.');
            return;
        }

        $lat = (float) request('latitude');
        $lng = (float) request('longitude');

        $response = Http::timeout(5)->get('https://api.maptiler.com/geocoding/' . $lng . ',' . $lat . '.json', [
            'key' => $key,
        ]);

        if ($response->failed() || empty($response->json('features'))) {
            $fail('We could not validate this location. Please drop the pin again.');
        }
    }
}
