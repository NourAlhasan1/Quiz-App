<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StudentAuthenticated
{

        public function handle($request, Closure $next)
        {
            if (auth()->check() && auth()->user()->role == 0) {
                return $next($request);
            }



            return redirect()->route('login');
        }
}
