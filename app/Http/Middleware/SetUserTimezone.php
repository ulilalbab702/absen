<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SetUserTimezone
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userTimezone = Auth::user()->timezone;

            // Set the timezone for this user's session
            if ($userTimezone) {
                config(['app.timezone' => $userTimezone]);
            }
        }

        return $next($request);
    }
}
