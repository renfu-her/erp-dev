<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasPermission
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        $flatten = collect($permissions)
            ->flatMap(function ($value) {
                return explode('|', $value);
            })
            ->filter()
            ->values()
            ->all();

        if (empty($flatten)) {
            return $next($request);
        }

        if ($user->hasAnyPermission($flatten)) {
            return $next($request);
        }

        abort(403);
    }
}
