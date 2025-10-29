<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle($request, Closure $next)
    {
        // Log the request body
        // Log::info('Request Body: ' . json_encode($request->all()));

        return $next($request);
    }
}