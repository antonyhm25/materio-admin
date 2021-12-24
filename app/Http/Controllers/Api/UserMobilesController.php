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
use App\Http\Resources\MealDeals\MealDealResult;
use App\Http\Resources\Users\UserResult;
use App\Models\MealDeal;

class UserMobilesController extends Controller
{
    public function mealDeals(User $user) 
    {
        $this->authorize('viewAny', $user);

        try {
            $mealDeals = MealDeal::take(15)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return MealDealResult::collection($mealDeals);
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
            'enable' => 'required|in:0,1'
        ]);
    
        try {
            DB::beginTransaction();

            $user = new User($request->all());
            $user->password = bcrypt($request->password);
            $user->type = 'local';
            $user->save();
            
            $user->assignRole(RolesType::LOCAL);

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
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id), 
                'email',
            ], 
            'enable' => 'required|in:0,1',
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
}