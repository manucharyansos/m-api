<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\CreateUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }
    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request['name'];
            $user->last_name = $request->input('last_name');
            $user->age = $request->input('age');
            $user->address = $request->input('address');
            $user->contact = $request->input('contact');
            $user->bio = $request->input('bio');
            $user->gender = $request->input('gender');
            $user->birthday = $request->input('birthday');
            if ($request->file('image')) {
                $image = $request->file('image');
                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user-images'), $fileName);
                $user->image = $fileName;
            }
            $user->save();
            Mail::to($request->user())->send(new CreateUser($user));
            return response()->json($user, 200);
        } catch (\Exception $e)
        {
            return response()->json($e->getMessage(),  404);
        }
    }
}
