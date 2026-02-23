<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
        if ($request->is('api/*')) {

            $status = 500;

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $status = $e->getStatusCode();
            }

            if ($status === 404) {
                $message = 'City Not found';
            } elseif ($status >= 400 && $status < 500) {
                $message = 'Resource not found';
            } elseif ($status >= 500) {
                if (app()->environment('local')) {
                    $message = $e->getMessage();   
                } else {
                    $message = 'Server Error';     
                }
                Log::error($e);
            }

            return response()->json([
                'status' => $status,
                'error' => $message,
            ], $status);
        }
    });
    })->create();
