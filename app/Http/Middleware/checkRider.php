<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class checkRider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('Rider'))
        {
            return $next($request);
        }
        else
        {
            return redirect('/riderLogin');
        }
    }
}
