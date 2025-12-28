<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    /**
     * Fetch simple current conditions (temp + wind) from Open-Meteo.
     * Returns null on error or missing coordinates.
     */
    public function getCurrent(float $latitude, float $longitude): ?array
    {
        $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m,wind_direction_10m,wind_gusts_10m',
        ]);

        if ($response->failed() || !$response->json('current')) {
            return null;
        }

        $current = $response->json('current');

        return [
            'temperature' => $current['temperature_2m'] ?? null,
            'wind_speed' => $current['wind_speed_10m'] ?? null,
            'wind_direction' => $current['wind_direction_10m'] ?? null,
            'wind_gusts' => $current['wind_gusts_10m'] ?? null,
            'observed_at' => $current['time'] ?? null,
        ];
    }
}
