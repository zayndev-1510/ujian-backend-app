<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use App\Models\User as user;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
class UserController extends Controller
{
    public function login(Request $r){

        try {
            $data=[
                "username"=>"required|string|min:2",
                "password"=>"required|string|min:5"
            ];
            $validasi=Validator::make($r->all(),$data);
            if($validasi->fails()){
                return response()->json($validasi->errors()->toJson(),400);
            }
            $data=[
                "username"=>$r->username,"password"=>$r->password
            ];

            if (! $token = JWTAuth::attempt($data)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        $user=auth()->user();
        return response()->json(compact('token','user'));
    }

    public function register(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|min:5',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:5',
            "username"=>"required|string|min:2|unique:users"
        ]);

        if($validator->fails()) return response()->json($validator->errors()->toJson(), 409);

       $user= user::create([
            "name"=>$r->name,
            "email"=>$r->email,
            "password"=>Hash::make($r->password),
            "username"=>$r->username
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),200);

    }

    public function loadDataUser()
    {
        $user=user::paginate(10);
        return response()->json($user);
    }

}
