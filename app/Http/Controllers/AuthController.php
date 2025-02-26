<?php

namespace App\Http\Controllers;

use App\ResponseHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseHelperTrait;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            return $this->successResponse(['user' => Auth::user(), 'access_token' => $token], 'Login successful',);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
