<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['This credential doesn\'t match to our records!']
            ], 404);
        }

        $token = $user->createToken('my_app_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //this is for log out

    public function logout(Request $request)
    {
        if($request->user()){
            $request->user()->tokens()->delete();
            return "Log out successfully!";
        }
    }

    public function store(Request $request)
    {
        //validation here
        $validation = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'firstname' => 'required',
            'lastname' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users',
            'profile_pic' => 'required'

        ]);
        $response = [];

        //check the validation if there are errors
        if ($validation->fails()) {
            $response["errors"] = $validation->errors();
            $response["code"] = 400;
        } else {
            DB::beginTransaction();
            try {
                //save
                $data = $request->all();
                $data["password"] = Hash::make($data["password"]);

                $fileName = time().$request->file('profile_pic')->getClientOriginalName();
                $path = $request->file('profile_pic')->storeAs('images', $fileName, 'public');
                $data["profile_pic"] = '/storage/'.$path;

                $user = User::create($data);
                $response["last_inserted_id"] = $user->id;
                $response["code"] = 200;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $response["errors"] = ["message" => "The user was not created!". $e];
                $response["code"] = 400;
            }
        }
        return response($response, $response["code"]);
    }
}
