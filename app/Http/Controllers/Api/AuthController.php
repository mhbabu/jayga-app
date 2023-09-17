<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller{

    public function userList(){

        $users   = User::all();
        $data = UserResource::collection($users);

        return response(['data' => $data], Response::HTTP_OK);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'name'  => 'required|string|max:255', 
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors(); 
            return response()->json(['error' => $errors], 422);    
        } 

        if ($validator->passes()) {
              
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email, 
                'password' => Hash::make($request->password)
            ]); 

            $data['token'] = $user->createToken('auth_token')->plainTextToken;
            $data['user']  =  new UserResource($user);
            return response($data, Response::HTTP_OK);    
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid credential',
                'error' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt(['email'=> $request->email, 'password'=>$request->password])) {
            $user = User::where('email', $request->email)->first();
            $data['token'] = $user->createToken('auth_token')->plainTextToken;
            $data['user']  =  new UserResource($user);
            return response($data, Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=> 'User logged out successfully.'], Response::HTTP_OK);
    }
}