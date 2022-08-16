<?php

namespace App\Http\Controllers\Api\PlanManager;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct()
    {
        // We set the guard api as default driver
        auth()->setDefaultDriver('api');
    }

    public function authUser(){
        try {
            $user = auth('api')->userOrFail();
        }catch (TokenInvalidException $e) {
            return response()->json(['has_error'=>true, 'msg' => 'Invalid token'],401);
        }
        catch (TokenExpiredException $e) {   
            return response()->json(['has_error'=>true, 'msg' => 'Token has Expired'],401);
        }
        catch (JWTException $e) {   
            return response()->json(['has_error'=>true, 'msg' => 'Token not parsed'],401);
        }
        return $user;
    }

}
