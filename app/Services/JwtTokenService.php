<?php

namespace App\Services;

use App\Models\User;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Illuminate\Http\Request;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtTokenService
{
    /**
     * returns string of generated authentication token
     * @param Int $userId
     * @return String
     */
    public static function getAuthTokenString(Int $userId): string
    {
        $signer = new Sha256();
        $appKey = env('APP_KEY');
        $token = (new Builder())
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 3600 * 24) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600 * 24) // Configures the expiration time of the token (exp claim)
            ->set('uid', $userId) // Configures a new claim, called "uid"
            ->sign($signer, $appKey)
            ->getToken(); // Retrieves the generated token
            
        return "{$token}";
    }

    /**
     * gets user id from token string
     * @return Int
     */
    public static function getUserId(): string
    {
        $request = app('request');
        $tokenString = $request->bearerToken();
        $token = (new Parser())->parse((string) $tokenString);

        return $token->getClaim('uid');
    }
}
