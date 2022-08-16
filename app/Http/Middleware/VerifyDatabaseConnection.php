<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class VerifyDatabaseConnection
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
        if($request->has('app_name'))
        {
            if($request->app_name == "plan_on_track")
            {
                Config::set('database.default', 'plan_on_track');
                return $next($request);
            }elseif($request->app_name == "planaji") {
                return $next($request);
            }else{
                $response = array(
                    'settings'=>[
                        "status" => 0,
                        "message" => "Invalid app name"
                    ]
                );
                return response()->json($response, 412);
            }
        } else {
            return $next($request);
        }
    }
}
