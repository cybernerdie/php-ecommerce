<?php

namespace Core\Middlewares;

use App\Core\Request;
use App\Middlewares\Authenticated;

class Middleware
{
    public const MAP = [
        'auth' => Authenticated::class
    ];

    public static function handle($key, Request $request)
    {
        if (!$key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new \Exception("No matching middleware found for key '{$key}'.");
        }

        $middlewareInstance = new $middleware();
        $middlewareInstance->handle($request);
    }
}