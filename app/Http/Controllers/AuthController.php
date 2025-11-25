<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        ]);


        if ($v->fails()) return response()->json($v->errors(), 422);


        $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
        ]);


        $token = auth('api')->login($user);


        return $this->respondWithToken($token);
    }


    public function login(Request $request)
    {
        $credentials = $request->only(['email','password']);
        if (!$token = auth('api')->attempt($credentials)) {
        return response()->json(['message' => 'Unauthorized'], 401);
        }


        return $this->respondWithToken($token);
    }


    public function me()
    {
        return response()->json(auth('api')->user());
    }


    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        $newToken = auth('api')->refresh();
        return $this->respondWithToken($newToken);
    }
}