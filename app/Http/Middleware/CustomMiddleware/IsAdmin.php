<?php

namespace App\Http\Middleware\CustomMiddleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    use \App\Classes\CustomResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // //check if user admin after verification and authentication
        // return (auth()->user()->type!='admin')
        //     ? redirect()->route('home')->with(['message'=>'Your account does not have the required privilege to access this page '])
 
        return (auth()->user()->type != 'admin')
            ? $this->failure(
                "Your account does not have the required privilege to access this page ",
                [],
                403
            )
            :$next($request);
    }
}

