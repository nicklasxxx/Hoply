<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;

class UserExist
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

        if( !User::checkUserExisit(Auth::user()->global_id) )
        {
            DB::table('users')->where('id', Auth::id())->delete();
        }

     return $next($request);
    }
}
