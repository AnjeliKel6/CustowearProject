<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle($request, Closure $next, $type)
    {
        if (Auth::check() && Auth::user()->utype === $type) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Access denied.');
    }
}
