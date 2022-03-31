<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;


class UserAuthController extends Controller
{

    public function loginUser(LoginRequest $request)
    {
        try {
            $params = $request->all();

            //if invalid login details
            if (!auth()->attempt($params)) {
                return response()->json(['message' => 'Incorrect Details. Please try again']);
            }

            //generating access token using passport
            $token = auth()->user()->createToken('API Token')->accessToken;

            return response(["type" => "success", 'token' => $token]);

        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    
}
