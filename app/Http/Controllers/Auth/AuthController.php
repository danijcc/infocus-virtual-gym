<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;


class AuthController extends Controller
{  
    // endpoint register
   public function register (Request $request){
    
    Log::info($request);
    $validator = Validator::make($request->only(
        'name',
        'email',
        'password',
        'password_confirmation'), 
        [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required ',
    ]);

    if($validator->fails()){
        return response()->json($validator->errors()->toJson(),400);
    }

    $user = User::create([
        'name'=>$request->get('name'),
        'email'=>$request->get('email'),
        'password'=>Hash::make($request->get('password')),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user','token'),201);
    
   }
       // endpoint login
    public function login (LoginRequest $request){
        
        $credencials = $request->only('email', 'password');
            try{
                if(!$token = JWTAuth::attempt($credencials)) {
                    return response()->json([
                        'error' => 'invalid credencials'
                    ], 400);
                }
            }
             catch  (JWTException $e) {
                return response()->json([
                    'error' => 'not create token'
                ], 500);
            }     

        return response()->json(compact('token'));    
    }
}
