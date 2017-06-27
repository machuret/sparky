<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
        case 'employee':
          if (Auth::guard($guard)->check()) {
            return redirect()->route('employee.dashboard',['tenant' => $request->username]);
          }
          break;
        case 'client':
          if (Auth::guard($guard)->check()) {
            return redirect()->route('client.dashboard',['tenant' => $request->username]);
          }
          break;
        default:
          if (Auth::guard($guard)->check()) {
            return redirect()->route('dashboard');
          }
          break;
      }

      return $next($request);
    }
}
