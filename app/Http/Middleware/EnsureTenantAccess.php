<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('School Admin')) {
            if (!$user->school_id || $user->school?->status !== 'active') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your school account is either unassigned or inactive.');
            }
        }

        return $next($request);
    }
}
