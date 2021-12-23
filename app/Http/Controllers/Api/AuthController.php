<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\RolesType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Auth\UserAuthenticated;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'deviceName' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [trans('app.general.auth')],
            ]);
        }

        $permissions = $user->getAllPermissions()->map(function ($item) {
            return "{$item->name}";
        });

        return $user->createToken($request->deviceName, $permissions->toArray())->plainTextToken;
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response(trans('app.general.logout'));
    }

    public function me()
    {
        $user = auth()->user();

        return response()->json(new UserAuthenticated($user));
    }
}
