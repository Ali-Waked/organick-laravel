<?php

use App\Http\Middleware\CheckApiKeyISValid;
use App\Http\Middleware\MakingUserTypeMember;
use App\Http\Middleware\UpdateUserLastActiveAt;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        // channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', CheckApiKeyISValid::class);
        $middleware->append(UpdateUserLastActiveAt::class);
        $middleware->alias(['MakingUserTypeMember' => MakingUserTypeMember::class, 'role' => \App\Http\Middleware\RoleMiddleware::class]);
        // $middleware->
        $middleware->statefulApi();
        $middleware->validateCsrfTokens([
            'payment/stripe/success',
        ]);
    })
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )

    //

    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->
    })->create();
