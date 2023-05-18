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
       public function login (LoginRequest $request)
       {
           dd($request->all());
        //    $credentials = $request->only('email', 'password');
   
        //    if (! $token = auth()->attempt($credentials)) {
        //        return response()->json(['error' => 'Unauthorized'], 401);
        //    }
   
        //    return $this->respondWithToken($token);
       }
}
