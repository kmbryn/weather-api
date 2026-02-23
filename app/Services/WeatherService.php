<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WeatherService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct() {
        $this->baseUrl = config('services.openweather.base_url');
        $this->apiKey = config('services.openweather.api_key');

    }
    
    public function getWeatherByCity(string $city) : array 
    {
        try{

            $response = Http::baseUrl($this->baseUrl)
                ->timeout(20)
                ->get('/weather', [
                    'q' => $city,
                    'appid' => $this->apiKey
                ])->throw();

            return $response->json();

        }catch(RequestException $ex){
            $status = $ex->response->status();

            if ($status === 404) {
                throw new HttpException(404, 'City not found.');
            }

            throw new HttpException(502, 'Weather service unavailable.');
        }
       
    }

    public function getCachedWeather(string $city) : array
    {
        $cacheKey = "weather:{$city}";

        if(Cache::has($cacheKey))
        {
            return [
                'data' => Cache::get($cacheKey),
                'source' => 'cache'
            ];
        }

        $weather = $this->getWeatherByCity($city);
        Cache::put($cacheKey, $weather, now()->addMinutes(10));

        return [
            'data' => $weather,
            'source' => 'external'
        ];
    }
}