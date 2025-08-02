<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CheckPermission;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // Sử dụng cùng một class Authenticate cho cả 'auth' và 'auth:sanctum'
            'auth' => Authenticate::class,
            'auth:sanctum' => Authenticate::class,

            // Middleware tùy chỉnh
            'is_admin' => IsAdmin::class,
            'permission' => CheckPermission::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
