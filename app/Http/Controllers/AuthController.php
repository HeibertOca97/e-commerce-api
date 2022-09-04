<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|min:6|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20'
        ]); 

        if($validator->fails()){
            return response([
                'success' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }


        try{
            if(User::all()->count() < 1){
                $request['isAdmin'] = 1;
            }

            $request['password'] = Hash::make($request->password);

            User::create($request->all());

            return response([
                'success' => true,
                'message' => 'User created successfully.' 
            ], Response::HTTP_CREATED); //201

        }catch(\Throwable $err){

            return response([
                'success' => false,
                'error' => $err->getMessage()
            ], Response::HTTP_NOT_MODIFIED); // 304
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|min:6|max:20',
            'password' => 'required|string'
        ]); 

        if($validator->fails()){
            return response([
                'success' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        $user = User::where('username', $request->username)->first(); 

        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'success' => false,
                'error' => 'Wrong credentials!',
            ], Response::HTTP_NOT_FOUND); // 401 o 404
        }        

        $data = $user->createAndReturnToken($user);

        return response([
            'success' => true,
            'data' => $data 
        ], Response::HTTP_OK);
    }

    public function logout(Request $request){        
        $token_id = $request->token_id;
        Auth::user()->tokens()->where('id', $token_id)->delete();

        return response([
            'success' => true,
            'message' => 'Session successfully revoked',
        ], Response::HTTP_OK);

    }

}
