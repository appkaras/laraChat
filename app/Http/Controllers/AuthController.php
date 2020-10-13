<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email',$email)->first();

        if ($user && Hash::check($password,$user->password)){
            return response()->json(['user'=>$user,'success'=>true]);
        }

        return response()->json(['success'=>true]);
    }

    public function register(Request $request){
        Validator::make($request->all(),[
            'name' => ['required','string','max:18'],
            'email'=> ['required','email','max:30','unique:users'],
            'password' => ['required','string','min:6'],
        ])->validate();

        return User::created([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60),
        ]);
    }
}
