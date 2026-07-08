<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'buyer'            => \App\Http\Middleware\EnsureBuyer::class,
        'admin'            => \App\Http\Middleware\EnsureAdmin::class,
        'admin.or.kurator' => \App\Http\Middleware\EnsureAdminOrKurator::class,
        'admin.timeout'    => \App\Http\Middleware\AdminSessionTimeout::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
