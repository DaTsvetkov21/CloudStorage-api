<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use TheSeer\Tokenizer\Token;

class AuthController extends Controller

{

    public function index()
    {
        return User::get('email');

    }


    public function registration()
    {
        if ($this->loginValidation(request()) === 'login and password is valid') {
            User::create([
                'email' => request()->email,
                'password' => request()->password,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return response()->json([
                'message' => [
                    'status' => 'success',
                    'token' => $this->generateTokenAuth()
                ]
            ]);
        }
        else {
            return $this->loginValidation(request());
        }
    }

    public function loginValidation($request)
    {
        if (!request()->email or !request()->password) {
            return response()->json([
                'error' => "email and password required",
            ], 422);
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL))
        {
            return response()->json([
                    'error' => 'email must be valid'
                ], 422);
        }
        elseif (strlen($request->password) < 4) {
            return response()->json([
                'error' => 'password must be more than 3 characters'
            ], 422);
        }
        elseif (str_contains(User::all('email'), $request->email)) {
            return response()->json([
                'error' => 'this email is used'
            ], 422);
        }
        else {
            return 'login and password is valid';
        }
    }

    function generateTokenAuth($length = 50): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
