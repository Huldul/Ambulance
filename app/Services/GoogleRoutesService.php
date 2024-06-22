<?php
// app/Services/GoogleRoutesService.php
// app/Services/GoogleRoutesService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleRoutesService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Игнорирование проверки сертификата для отладки
        ]);
        $this->apiKey = config('services.google_maps.key'); // Изменение метода получения ключа

        Log::info('Google Maps API Key', ['key' => $this->apiKey]); // Логирование ключа для отладки
    }

    public function getRoute($origin, $destination, $teamId)
    {
        $cacheKey = "route_{$teamId}";
        return Cache::remember($cacheKey, 3600, function() use ($origin, $destination) {
            $url = 'https://maps.googleapis.com/maps/api/directions/json';
            try {
                $response = $this->client->get($url, [
                    'query' => [
                        'origin' => $origin,
                        'destination' => $destination,
                        'key' => $this->apiKey
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                Log::info('Google Routes API response', ['data' => $data]);
                return $this->formatResponse($data);
            } catch (\Exception $e) {
                Log::error('Error fetching route from Google Routes API', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    protected function formatResponse($data)
    {
        if (empty($data['routes'])) {
            Log::warning('No routes found in the response', ['data' => $data]);
            return null;
        }

        $route = $data['routes'][0];
        $leg = $route['legs'][0];

        return [
            'distance' => $leg['distance']['value'], // метры
            'duration' => $leg['duration']['value'], // секунды
            'polyline' => $route['overview_polyline']['points'],
            'end_location' => $leg['end_location'],
        ];
    }
}
