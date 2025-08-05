<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
       $middleware->alias([
        'role' => \jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyRole::class,
        'permission' => \jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyPermission::class,
        'level' => \jeremykenedy\LaravelRoles\App\Http\Middleware\VerifyLevel::class,
       ]);


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();