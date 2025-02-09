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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            logger(get_class($e));
            logger($e->getMessage());
            if (isApiRequest(request: $request)) {
                return match (true) {
                    $e instanceof \Illuminate\Validation\ValidationException => apiResponse(data: [
                        'errors' => $e->errors(),
                    ], message: $e, status: \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY),
                    default => apiResponse(data: [], message: $e)
                };
            }
        });
    })->create();
