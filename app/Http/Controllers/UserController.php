<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::get();
        return response()->json(['data'=>$users]);
    }


    public function show(User $user): JsonResponse
    {
        return response()->json(['data'=>$user]);
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|max:20',
            'password' => ['required', Password::min(8)->letters()->numbers()], #todo confirm
            'full_name' => 'required|string|max:200'
        ]);

        if ($validator->fails())
            return response()->json(['Error'=>$validator->errors()], 400);

        $new_user = new User([
            'user_name' => $request['user_name'],
            'password' => $request['password'],
            'full_name' => $request['full_name'],
            'user_rights' => 'user',
        ]);

        try {
            $new_user->save();

            return response()->json([
                'Success' => 'New user is saved',
                'data' => $new_user,
            ], 201);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage(), 'data'=>$new_user], 500);
        }
    }


    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'max:20',
            'password' => [Password::min(8)->letters()->numbers()], #todo confirm
            'full_name' => 'string|max:200',
            'rights' => 'in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json(['Error'=>$validator->errors()], 400);
        }

        $user_rights = (User::find($request['client_user_id']))['rights'];
        if ($user_rights != 'admin') {
            return response()->json(['Error'=>'You have no rights to change a user'], 403);
        }

        $user['user_name'] = $request['user_name'] ?: $user['user_name'];
        $user['password'] = $request['password'] ?: $user['password'];
        $user['full_name'] = $request['full_name'] ?: $user['full_name'];
        $user['rights'] = $request['rights'] ?: $user['rights'];

        try {
            $user->save();
            return response()->json(['Success'=>'User updated', 'data' => $user]);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()], 500);
        }
    }


    public function destroy(User $user, Request $request): JsonResponse
    {
        $user_rights = (User::find($request['client_user_id']))['rights'];

        if ($user_rights != 'admin') {
            return response()->json(['Error'=>'You have no rights to delete a user', 'data'=>$user], 403);
        }

        try {
            $user->delete();
            return response()->json(['Success'=>'User deleted', 'data'=>$user]);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()], 500);
        }
    }
}
