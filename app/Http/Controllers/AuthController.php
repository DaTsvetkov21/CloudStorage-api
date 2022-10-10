<?php

namespace App\Http\Controllers;

use App\Models\AuthToken;
use App\Models\User;

class AuthController extends Controller

{

    public function index()
    {
        $user = User::all()->where('email', 'fasfa@mail.ru');
        return $user->value('email');

    }


    public function registration(): object
    {
        if (str_contains(User::all('email'), request()->email)) {
            return response()->json([
                'error' => 'this email is used'
            ], 422);
        } elseif ($this->validationAuthForm(request()) === 'login and password is valid') {
            User::create([
                'email' => request()->email,
                'password' => request()->password,
            ]);
            $user = User::all()->where('email', request()->email);
            return response()->json([
                'message' => [
                    'status' => 'success',
                    'user_id' => $user->value('id'),
                    'email' => request()->email

                ]
            ]);
        } else {
            return $this->validationAuthForm(request());
        }
    }

    public function authorization(): object
    {
        $user = User::all()->where('email', request()->email);
        if ($this->validationAuthForm(request()) === 'login and password is valid') {
            if ($user->value('email') === request()->email && $user->value('password') === request()->password) {
                AuthToken::create([
                    'user_id' => $user->value('id'),
                    'auth_token' => $this->generateTokenAuth(),
                ]);
                return response()->json([
                    'message' => [
                        'status' => 'success',
                        'user_id' => $user->value('id'),
                        'auth_token' => $this->generateTokenAuth()
                    ]
                ]);
            } else {
                return response()->json([
                    'error' => 'email or password is incorrect'
                ], 422);
            }
        } else {
            return $this->validationAuthForm(request());
        }
    }

    public function validationAuthForm($request)
    {
        if (!request()->email or !request()->password) {
            return response()->json([
                'error' => "email and password required",
            ], 422);
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'error' => 'email must be valid'
            ], 422);
        } elseif (strlen($request->password) < 4) {
            return response()->json([
                'error' => 'password must be more than 3 characters'
            ], 422);
        } else {
            return 'login and password is valid';
        }
    }

    private function generateTokenAuth($length = 50): string
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
