<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $method)
    {
        if (auth('admin')->user()->can($permission)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
