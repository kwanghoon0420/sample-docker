<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Services\OrderActionPolicy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Admin2Middleware
{
    /**
     * admin2 영역 접근 제어
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401);
        }

        if (!OrderActionPolicy::isAdmin((int) $user->id)) {
            abort(403);
        }

        return $next($request);
    }
}

