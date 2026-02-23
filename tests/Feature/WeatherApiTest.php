<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{ 
    /** @test */
    public function it_returns_weather_successfully()
    {
        
        Http::fake([
            '*' => Http::response([
                'name' => 'Manila',
                'dt' => 1700000000,
                'main' => ['temp' => 30],
                'weather' => [['description' => 'clear sky']],
            ], 200)
        ]);

        $response = $this->getJson('/api/weather/Manila');

        $response->assertOk()
            ->assertJsonPath('data.city', 'Manila')
            ->assertJsonPath('data.source', 'external');
    }

    /** @test */
    public function it_returns_404_when_city_not_found()
    {
        Http::fake([
            '*' => Http::response([], 404)
        ]);

        $response = $this->getJson('/api/weather/InvalidCity');

        $response->assertStatus(404);
    }
}
