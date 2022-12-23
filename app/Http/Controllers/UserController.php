<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use JWTAuthException;


class UserController extends Controller
{

    public function getLogin(Request $request)
    {
        return response()->json(array(
            $status = $request->get('name'),
            $data = [
                "message" => $request->get('email')
            ]
        ));
    }
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    // public function login(Request $request){
    //     $credentials = $request->only('email', 'password');
    //     $token = null;
    //     try {
    //        if (!$token = JWTAuth::attempt($credentials)) {
    //         return response()->json(['invalid_email_or_password'], 422);
    //        }
    //     } catch (JWTAuthException $e) {
    //         return response()->json(['failed_to_create_token'], 500);
    //     }
    //     return response()->json(compact('token'));
    // }
    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        return response()->json(['result' => $user]);
    }

    public function getData(Request $request)
    {
        $this->user = User::where("email", "=", $request->get("email"));
        return $this->user;
    }
}
