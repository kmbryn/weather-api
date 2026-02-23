# Weather API (Laravel)

## Requirements
- PHP 8.2+
- Composer
- OpenWeatherMap API Key

---

## Setup

1. Clone the repository

2. Install dependencies
<code>composer install</code>

3. Copy Environment File
<code>cp .env.example .env</code>
4. Add your API key
```bash
OPENWEATHER_API_KEY=<YOUR-API-KEY>
OPENWEATHER_BASE_URL=https://api.openweathermap.org/data/2.5
```
5. Generate app key
<code>php artisan key:generate</code>
6. Run server
<code>php artisan serve</code>
---

## Endpoints

```bash
GET /api/weather/{city}  
GET /api/weather/{city}/cached  
DELETE /api/weather/{city}/cached
```
You can use Postman and import the <code>Weather API.postman_collection.json</code> to test all endpoints.
Just change the <code>base_url</code> variable to be the same with <code>APP_URL</code> from you .env file.
Or you can also access the <code>GET</code> endpoints by directly accessing it from the browser.

---

## Run Tests
<code>php artisan test tests/Feature/WeatherApiTest.php</code>

---

## Architecture Overview

- Controller: Handles request validation and response formatting
- Service: Encapsulates external API logic and caching
- Resource: Transforms API response
- Global exception handling ensures consistent JSON errors
- Added /api prefix to separate the API Routes from Web Routes

Caching layer:
- 10 minute TTL
- Manual invalidation endpoint

External API failures are translated into proper HTTP responses (404, 502).


