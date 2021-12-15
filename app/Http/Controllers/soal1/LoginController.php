<?php

namespace App\Http\Controllers\soal1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "user_name" => "required",
            "password" => "required"
        ], [
            "user_name.required" => "Missing key : user_name",
            "password.required" => "Missing key : password",
        ]
        );

        if ($validator->fails()) {
            return $this->base_response("-1", $validator->errors()->first());
        }
        
        $credentials = $request->only('user_name', 'password');

        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return $this->base_response("02", "user_name dan password tidak ditemukan");
        }

        return $this->base_response("00", "Login Berhasil", $token);

    }
}
