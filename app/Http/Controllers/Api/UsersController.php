<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Helpers\RolesType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Users\UserResult;
use App\Http\Resources\Users\UserPaginated;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $request->validate([
            'page' => 'sometimes|required|integer',
            'size' => 'sometimes|required|integer',
            'sortBy' => 'sometimes|required|in:name,email,enable,created_at',
            'order' => 'sometimes|required|in:asc,desc',
            'type' => 'sometimes|required',
            'search' => 'sometimes|required',
        ]);

        try{
            $size = $request->size ?? 15;
            $sortBy = $request->sortBy ?? 'full_name';
            $order = $request->order ?? 'asc';
            $type = $request->type ?? null;
            $search = $request->search ?? null;

            $query = User::query();
            if (!is_null($type) && $type === 'admin') {
                $query = $this->getUsersAdmin();
            } else {
                $query = $this->getUsersSystem();
            }

            $query = $query
                ->orderBy($sortBy, $order)
                ->search($search)
                ->byType($type)
                ->paginate($size);

            return new UserPaginated($query);
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    private function getUsersSystem() 
    {
        return  User::select(
            'id',
            'full_name as name',
            'email',
            'enable',
            'created_at as createdAt'
        );
    }
    
    private function getUsersAdmin() 
    {
        return  User::select(
            'users.id',
            'users.full_name as name',
            'users.email',
            'users.enable',
            'users.created_at as createdAt',
            'restaurants.name as restaurant'
        )
        ->join('restaurants', 'restaurants.user_id', '=', 'users.id');
    }
    
    public function show(User $user)
    {
        $this->authorize('view', $user);

        try {
            return new UserResult($user);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|alpha_dash',
            'repeatPassword' => 'required|same:password',
            'enable' => 'required|in:0,1',
        ]);
    
        try {
            DB::beginTransaction();

            $user = new User([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'full_name' => "{$request->firstName} {$request->lastName}",
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'enable' => $request->enable,
            ]);

            $user->type = 'system';
            $user->save();
            
            $user->assignRole(RolesType::SUPER_ADMIN);

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
        $this->authorize('update', $user);

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id), 
                'email',
            ], 
            'enable' => 'required|in:0,1',
        ]);
    
        try {
            $user->fill([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'full_name' => "{$request->firstName} {$request->lastName}",
                'email' => $request->email
            ]);
            $user->save();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            $user->delete();
            
            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroyMany(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $this->authorize('deleteMany', User::class);

        try {
            User::destroy($request->ids);
            
            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function passwordReset(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6|alpha_dash',
            'repeatPassword' => 'required|same:password'
        ]);

        $this->authorize('update', $user);

        try {
            $user->password = bcrypt($request->password);
            $user->save();
            
            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function passwordChange(Request $request, User $user) 
    {
        $this->authorize('update', $user);

        $request->validate([
            'password' => 'required',
            'newPassword' => 'required|min:6|alpha_dash',
            'repeatPassword' => 'required|same:newPassword'
        ]);

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [trans('app.general.auth')],
            ]);
        }

        try {
            $user->password = bcrypt($request->newPassword);
            $user->save();
            
            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
