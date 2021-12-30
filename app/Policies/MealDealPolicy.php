<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MealDeal;
use App\Helpers\RolesType;
use App\Helpers\PermissionsType;
use App\Models\Restaurant;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealDealPolicy
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
        if ($user->tokenCan(PermissionsType::MEAL_DEALS_VIEW)) {
           return true;
        }

        return Response::deny(trans('app.general.forbidden'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MealDeal  $mealDeal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MealDeal $mealDeal)
    {
        if ($user->tokenCan(PermissionsType::MEAL_DEALS_VIEW)) {
            if (!is_null($user->restaurant)) {
                return $user->restaurant->id === $mealDeal->meal->restaurant_id
                    ? Response::allow()
                    : Response::deny(trans('app.general.owner'));
            }
        }

        return Response::deny(trans('app.general.forbidden'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Restaurant $restaurant)
    {
        // 
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MealDeal  $mealDeal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MealDeal $mealDeal)
    {
        if ($user->tokenCan(PermissionsType::MEAL_DEALS_STATUS)) {
            return is_null($mealDeal->user_id)
                ? Response::allow()
                : Response::deny(trans('app.meal-deals.taken'));
        }

        if ($user->tokenCan(PermissionsType::MEAL_DEALS_CREATE_UPDATE)) {
            if (!is_null($user->restaurant)) {
                return $user->restaurant->id === $mealDeal->meal->restaurant_id
                    ? Response::allow()
                    : Response::deny(trans('app.general.owner'));
            }
        }

        return Response::deny(trans('app.general.forbidden'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MealDeal  $mealDeal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MealDeal $mealDeal)
    {
        if ($user->tokenCan(PermissionsType::MEAL_DEALS_DELETE)) {
            if (!is_null($user->restaurant)) {
                if ($user->restaurant->id !== $mealDeal->meal->restaurant_id) {
                    Response::deny(trans('app.general.owner'));
                }

                if (!is_null($mealDeal->user_id)) {
                    return Response::deny(trans('app.meal-deals.taken'));;
                }

                return Response::allow();
            }
        }

        return Response::deny(trans('app.general.forbidden'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MealDeal  $mealDeal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MealDeal $mealDeal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MealDeal  $mealDeal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MealDeal $mealDeal)
    {
        //
    }
}
