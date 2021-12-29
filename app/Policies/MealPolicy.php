<?php

namespace App\Policies;

use App\Helpers\PermissionsType;
use App\Helpers\RolesType;
use App\Models\Meal;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
       //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Meal $meal)
    {
        if ($user->tokenCan(PermissionsType::MEALS_VIEW)) {
            if ($user->hasRole(RolesType::SUPER_ADMIN)) {
                return true;
            }
        
            if (!is_null($user->restaurant)) {
                return $user->restaurant->id === $meal->restaurant_id
                    ? Response::allow()
                    : Response::deny('app.general.owner');
            }
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Meal $meal)
    {
        if ($user->tokenCan(PermissionsType::MEALS_CREATE_UPDATE)) {
            if (!is_null($user->restaurant)) {
                return $user->restaurant->id === $meal->restaurant_id
                    ? Response::allow()
                    : Response::deny('app.general.owner');
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Meal $meal)
    {
        if ($user->tokenCan(PermissionsType::MEALS_CREATE_UPDATE)) {
            if (!is_null($user->restaurant)) {
                return $user->restaurant->id === $meal->restaurant_id
                    ? Response::allow()
                    : Response::deny('app.general.owner');
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Meal $meal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Meal $meal)
    {
        //
    }
}
