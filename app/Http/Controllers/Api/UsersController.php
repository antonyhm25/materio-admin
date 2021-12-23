<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesType;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserDetail;
use App\Http\Resources\Users\UserCreated;
use App\Http\Resources\Users\UserPaginated;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
            'size' => 'required|integer',
            'sortBy' => 'required|in:name,email,enable,created_at',
            'order' => 'required|in:asc,desc',
        ]);

        try{
            $search = $request->has('search') ? $request->search : null;

            $query = User::query();

            $query->orderBy($request->sortBy, $request->order)
                ->search($search);

            return new UserPaginated($query->paginate($request->size));
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
    
    public function show(User $user)
    {
        try {
            return new UserDetail($user);
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
            'role' => 'sometimes|required|exists:roles,name'
        ]);
    
        try {
            $user = new User($request->all());
            $user->password = bcrypt($request->password);
            $user->type = $request->has('role') ? 'ADMIN' : 'USER';
            $user->save();

            $role = $request->has('role') ?
                $request->role :
                RolesType::USER_MOBILE;
            
            $user->assignRole($role);

            return new UserCreated($user);
        } catch (Exception $ex) {
            Log::error($ex);

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

            $user->type = $request->role === RolesType::USER_MOBILE ? 'USER' : 'ADMIN';
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



