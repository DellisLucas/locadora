<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Override the default behavior to return JSON instead of redirect.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            abort(401, 'Unauthorized.');
        }

        return null;
    }
}
