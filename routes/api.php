<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;

Route::get('/weather/{city}', [WeatherController::class, 'show']);
Route::get('/weather/{city}/cached', [WeatherController::class, 'cached']);