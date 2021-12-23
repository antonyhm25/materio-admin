<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserPaginated;
use App\Http\Resources\Users\UserResult;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'size' => 'sometimes|required|integer',
            'sortBy' => 'sometimes|required|in:name,email,enable,created_at',
            'order' => 'sometimes|required|in:asc,desc',
            'search' => 'sometimes|required',
        ]);

        try{
            $size = $request->size ?? 15;
            $sortBy = $request->sortBy ?? 'name';
            $order = $request->order ?? 'asc';
            $search = $request->search ?? null;

            $query = User::orderBy($sortBy, $order)
                ->search($search)
                ->paginate($size);

            return new UserPaginated($query);
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
    
    public function show(User $user)
    {
        try {
            return new UserResult($user);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|alpha_num',
            'repeatPassword' => 'required|same:password',
            'enable' => 'required|in:0,1',
            'role' => 'required|exists:roles,name',
        ]);
    
        try {
            DB::beginTransaction();

            $user = new User($request->all());
            $user->password = bcrypt($request->password);
            $user->save();
            
            $user->assignRole($request->role);

            DB::commit();

            return new UserResult($user);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();

            return response(trans('app.general.error'), 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id), 
                'email',
            ], 
            'enable' => 'required|in:0,1',
            'role' => 'required|exists:roles,name'
        ]);
    
        try {
            $user->fill($request->all());
            $user->save();
        
            $user->assignRole($request->role);

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
