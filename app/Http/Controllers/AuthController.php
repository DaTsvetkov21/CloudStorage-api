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


    public function registration(): object
    {
        if (!request()->email or !request()->password) {
            return response()->json([
                'error' => "email and password required",
            ], 422);
        } elseif (str_contains(User::all('email'), request()->email)) {
            return response()->json([
                'error' => 'this email is used'
            ], 422);
        } elseif (strlen(request()->password) < 4) {
            return response()->json([
                'error' => 'password must be more than 3 characters'
            ], 422);
        } else {
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
    }

    public
    function loginValidation($request): bool
    {
        if ($request->validate(['email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'])
            ) {
            return true;
        } else {
            return false;
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
