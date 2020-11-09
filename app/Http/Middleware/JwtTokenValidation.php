<?php

namespace App\Http\Middleware;

use Closure;
use RuntimeException;
use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtTokenValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $signer = new Sha256();
        $appKey = env('APP_KEY');

        try {              
            $token = (new Parser())->parse((string) $token);          
            $token->getClaim('uid');
            if (time() <= $token->getClaim('exp')) {
                if ($token->verify($signer, $appKey)) {
                    $response = $next($request);
                    return $response;
                } else {
                    return response()->json('Token is forged', 401);
                }
            } else {
                return response()->json('Token is expired', 401);
            }
        } catch (\RuntimeException $e) {
            return response()->json('Token is forged', 401);
        } catch (\InvalidArgumentException $e) {
            return response()->json('No token is given', 401);
        }
    }
}
