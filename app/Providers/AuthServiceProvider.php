<?php

namespace App\Providers;

use App\Models\Meal;
use App\Models\MealDeal;
use App\Models\Restaurant;
use App\Models\User;
use App\Policies\MealDealPolicy;
use App\Policies\MealPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RestaurantPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        Restaurant::class => RestaurantPolicy::class,
        Meal::class => MealPolicy::class,
        MealDeal::class => MealDealPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
