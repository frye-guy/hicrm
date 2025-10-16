<?php

use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // other aliases you may already have...
$middleware->alias([
  'update.last.activity' => \App\Http\Middleware\UpdateLastActivity::class,
  'role' => \App\Http\Middleware\EnsureUserHasRole::class,
]);
    })
    ->withExceptions()
    ->create();
