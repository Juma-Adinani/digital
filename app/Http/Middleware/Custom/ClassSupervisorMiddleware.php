<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassSupervisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Helper::isClassSupervisor()) {
            return $next($request);
        }
        return redirect()->view('login');
    }
}
