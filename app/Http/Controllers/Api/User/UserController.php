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

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|numeric',
            'number' => 'required|numeric',
            'address' => 'required'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json(['user' => $user, 'User created successfully']);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
}
