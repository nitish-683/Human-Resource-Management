<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->routeIs('candidate.*')) {
            return $request->expectsJson() ? null : route('candidate.login');
        }

        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Handle unauthenticated users for multiple guards.
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = ['web']; // Default guard
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
