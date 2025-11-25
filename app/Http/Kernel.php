<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Global middleware
    ];

    

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.jwt' => \App\Http\Middleware\JwtMiddleware::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
