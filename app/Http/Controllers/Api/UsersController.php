<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCreated;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|alpha_num',
            'repeatPassword' => 'required|same:password',
            'enable' => 'required|in:0,1',
        ]);
    
        try {
            $user = new User($request->all());
            $user->password = bcrypt($request->password);
            $user->save();

            return new UserCreated($user);
        } catch (Exception $ex) {
            return response('error interno de servidor.', 500);
        }
    }
}
