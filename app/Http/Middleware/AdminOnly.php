<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $adminEmail = config('admin.email');

        if (auth()->check() && auth()->user()->email === $adminEmail) {
            return $next($request);
        }

        abort(403, 'Akses hanya untuk Admin.');
    }
}
