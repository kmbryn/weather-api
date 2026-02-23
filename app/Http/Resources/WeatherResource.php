<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class WeatherResource extends JsonResource
{
    protected string $source;

    public function __construct($resource, string $source)
    {
        parent::__construct($resource);
        $this->source = $source;
    }

    public function toArray(Request $request): array
    {
        return [
            'city' => $this['name'],
            'temperature' => $this['main']['temp'],
            'weather_description' => $this['weather'][0]['description'],
            'timestamp' => Carbon::createFromTimestampUTC($this['dt'])
                ->toIso8601String(),
            'source' => $this->source,
        ];
    }
}