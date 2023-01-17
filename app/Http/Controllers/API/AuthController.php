<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ApiController;

class AuthController extends BaseController
{
//    public function login(Request $request)
//    {
//        $data = $request->validate([
//            'email' => 'required|string',
//            'password' => 'required|string'
//        ]);
//
//        $user = User::where('email', $data['email'])->first();
//
//        if (!$user || !Hash::check($data['password'], $user->password)) {
//            return response([
//                'msg' => 'incorrect username or password'
//            ], 401);
//        }
//
//        $token = $user->createToken('apiToken')->plainTextToken;
//
//        $res = [
//            'user' => $user,
//            'token' => $token
//        ];
//
//        return response($res, 201);
//    }

//    public function register(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'email' => 'required|email|unique:users,email',
//            'password' => 'required',
//            'confirm_password' => 'required|same:password',
//        ]);
//
//        if($validator->fails()){
//            return $this->errorResponse('Validation error.', $validator->errors(), 400);
//        }
//
//        $data = $request->all();
//        $data['password'] = bcrypt($data['password']);
//        $user = User::create($data);
//
//        $token = $user->createToken('apiToken')->plainTextToken;
//
//        $res = [
//            'user' => $user,
//            'token' => $token
//        ];
//        return response()->json($res, 201);
//    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
