<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeanFacultyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Helper::isDeanFaculty()) {
            return $next($request);
        }
        return redirect()->view('login');
    }
}
