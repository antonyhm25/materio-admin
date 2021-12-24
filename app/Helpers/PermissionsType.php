<?php

namespace App\Helpers;

class PermissionsType
{
    public const PERMISSION_VIEW = 'permissions:view';

    public const ROLES_VIEW = 'roles:view';
    public const ROLES_CREATE_UPDATE = 'roles:create-update';
    public const ROLES_DELETE = 'roles:delete';
    
    public const USERS_VIEW = 'users:view';
    public const USERS_CREATE_UPDATE = 'users:create-update';
    public const USERS_DELETE = 'users:delete';

    public const ACCOUNT_VIEW = 'account:view';
    public const ACCOUNT_UPDATE = 'account:update';

    public const RESTAURANTS_VIEW = 'restaurants:view';
    public const RESTAURANTS_CREATE_UPDATE = 'restaurants:create-update';

    public const MEALS_VIEW = 'resturants:view';
    public const MEALS_CREATE_UPDATE = 'resturants:create-update';
    public const MEALS_DELETE = 'resturants:delete';

    public const MEAL_DEALS_VIEW = 'meal-deals:view';
    public const MEAL_DEALS_CREATE_UPDATE = 'meal-deals:create-update';
    public const MEAL_DEALS_DELETE = 'meal-deals:delete';
    public const MEAL_DEALS_STATUS = 'meal-deals:status';
}
