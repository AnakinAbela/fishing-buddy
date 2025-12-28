<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidCoordinates implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Latitude and longitude come as pairs; if either missing, skip API check
        // (base numeric validation still handles format/range).
        if (!request()->filled(['latitude', 'longitude'])) {
            return;
        }

        $lat = (float) request('latitude');
        $lng = (float) request('longitude');

        $response = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lng,
            'current' => 'temperature_2m',
        ]);

        if ($response->failed() || ! $response->json('current.temperature_2m')) {
            $fail('Location could not be validated against weather data. Please check the coordinates.');
        }
    }
}
