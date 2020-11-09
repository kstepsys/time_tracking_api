<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\JwtTokenService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        return response()->json(User::createUser($request->all()), 201);
    }

    /**
     * Retrieve jwt token for User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->passwordValid($request->password)) {
            return response()->json("Unauthorized", 401);
        }
        
        return response()->json(JwtTokenService::getAuthTokenString($user->id));
    }
}
