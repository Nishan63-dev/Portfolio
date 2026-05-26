<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        if (! $user || ! $user->is_admin) {
            return redirect()->route('login')->withErrors([
                'email' => 'Access denied. Administrative access is required.',
            ]);
        }

        return $next($request);
    }
}
