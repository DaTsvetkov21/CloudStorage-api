<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use TheSeer\Tokenizer\Token;
use function Symfony\Component\String\u;

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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
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

    public function authorization()
    {
        $user = User::all()->where('email', request()->email);
//        echo $user->value('password');
//        echo '   ';
//        echo request()->password;
        if ($this->validationAuthForm(request()) === 'login and password is valid') {
            if ($user->value('email') === request()->email && $user->value('password') === request()->password) {
                return response()->json([
                    'message' => [
                        'status' => 'success',
                        'user_id' => $user->value('id'),
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
