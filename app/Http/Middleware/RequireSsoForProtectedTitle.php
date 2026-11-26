<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSsoForProtectedTitle
{
    public function handle(Request $request, Closure $next): Response
    {
        $title = $request->route('title');

        if ($title !== null && $title->requires_sso === false) {
            return $next($request);
        }

        if ($request->user('sanctum') !== null) {
            return $next($request);
        }

        return response()->json([
            'code' => 'SSO_REQUIRED',
            'message' => 'Authentication is required to access this title.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
