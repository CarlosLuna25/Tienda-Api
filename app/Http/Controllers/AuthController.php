<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
        $attr = $this->validateLogin($request);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials mismatch', 401);
        }

        return $this->token($this->getPersonalAccessToken(),'Welcome');
    }

    public function signup(Request $request)
    {
        $attr = $this->validateSignup($request);

      $save =  User::create([
            'name' => $attr['name'],
            'surname' => $attr['surname'],
            'nickname' => $attr['nickname'],
            'email' => $attr['email'],
            'password' => Hash::make($attr['password']),
            'user_type'=>'user'
        ]);
        if ($save) {
            return $this->success('User Created', 201);
        }else{
            return $this->error('Error', 400);
        }

        Auth::attempt(['email' => $attr['email'], 'password' => $attr['password']]);

        
    }

    public function user()
    {
        return $this->success(Auth::user());
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        return $this->success('User Logged Out', 200);
    }

    public function getPersonalAccessToken()
    {
        if (request()->remember_me === 'true')
            Passport::personalAccessTokensExpireIn(now()->addDays(15));

        return Auth::user()->createToken('Personal Access Token');
    }

    public function validateLogin($request)
    {
        return $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    public function validateSignup($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'nickname' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}