<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function login(Request $request) {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return response([
                'meta' => [
                    'message' => 'failed login, error validation',
                    'code' => 422,
                    'status' => 'failed'
                ],
                'data' => $validation->errors()
            ], 422);
        }

        if (! $token = auth()->attempt($validation->validated())) {
            return response([
                'meta' => [
                    'message' => 'unauthorized',
                    'code' => 401,
                    'status' => 'failed'
                ],
                'data' => null
            ], 401);
        }

        return response([
            'meta' => [
                'message' => 'success login!',
                'code' => 200,
                'status' => 'success'
            ],
            'token' => $token
        ], 401);

    }

    public function register(Request $request) {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required'
        ];

        $validation = Validator::make($data, $rules);
        if($validation->fails()) {
            return response([
                'meta' => [
                    'message' => 'failed register, error validation',
                    'code' => 422,
                    'status' => 'failed'
                ],
                'data' => $validation->errors()
            ], 422);
        }

        $data['role'] = 'user';
        $data['password'] = Hash::make($request->password);

        $created = User::create($data);

        return response([
            'meta' => [
                'message' => 'success registered user',
                'code' => 201,
                'status' => 'success'
            ],
            'data' => null
        ], 201);
    }
}
