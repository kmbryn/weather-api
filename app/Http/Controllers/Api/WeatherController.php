<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WeatherResource;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;


class WeatherController extends Controller{


    public function __construct(
        protected WeatherService $weatherService
    ) {}

    public function show(string $city): WeatherResource
    {
        $weather = $this->weatherService
            ->getWeatherByCity($city);

        return new WeatherResource($weather, 'external');
    }

     public function cached(string $city): WeatherResource
    {
        $result = $this->weatherService
            ->getCachedWeather($city);

        return new WeatherResource(
            $result['data'],
            $result['source']
        );
    }

    public function invalidate(string $city): JsonResponse
    {
        Cache::forget("weather:{$city}");

        return response()->json([
            'message' => 'Cache cleared successfully.',
        ]);
    }

}