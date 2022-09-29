<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use DB;
use Illuminate\Http\Response;

class SalesPersonAuthMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if($request->has('accessToken'))
        {
            $accessToken = $request->accessToken;
            $authentication = DB::connection('bab');
            $result = $authentication->select("SELECT * FROM access_token WHERE value = ?", [$accessToken]);

            if(count($result))
            {
                return $next($request);
            } else {
                $response = new Response;
                $response->setStatusCode(401, 'Invalid Access Token');
                $response->header('WWW-Authenticate', 'Basic');

                return $response;
            }
        }
        $response = new Response;
        $response->setStatusCode(401, 'Invalid Access Token');
        $response->header('WWW-Authenticate', 'Basic');

        return $response;
    }
}
