<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Module Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    "modules" => [
        'roles' => 'Roles',
        'permissions' => 'Permisos',
        'users' => 'Usuarios',
        'accounts' => 'Cuenta de Usuario',
        'restaurants' => 'Restaurantes',
        'meals' => 'Comidas',
        'meal-deals' => 'Oferta de Comidas'
    ],

    'permissions' => [
        'permissions-view' => 'Ver Lista de Permisos',

        'roles-view' => 'Ver Lista de Roles',
        'roles-create-update' => 'Crear y Actualizar Roles',
        'roles-delete' => 'Eliminar Roles',

        'users-view' => 'Ver Lista de Usuarios',
        'users-create-update' => 'Crear y Actualizar Usuarios',
        'users-delete' => 'Eliminar Usuarios',

        'accounts-view' => 'Ver Mi Cuenta',
        'accounts-update' => 'Actualizar Mi Cuenta',
        
        'restaurants-view' => 'Ver Lista de Restaurantes',
        'restaurants-create-update' => 'Crear y Actualizar Restaurantes',
        'restaurants-delete' => 'Eliminar Restaurantes',

        'meals-view' => 'Ver Lista de Comidas',
        'meals-create-update' => 'Crear y Actualizar Comidas',
        'meals-delete' => 'Eliminar Comidas',

        'meal-deals-view' => 'Ver Lista de Ofertas de Comidas',
        'meal-deals-create-update' => 'Crear y Actualizar Ofertas de Comidas',
        'meal-deals-delete' => 'Eliminar Ofertas de Comidas',
        'meal-deals-status' => 'Cambiar Estados de las Ofertas de Comidas',
    ],

    'role-names' => [
        'super-admin' => 'Super Administrador',
        'admin-restaurant' => 'Administrador del Restaurante',
        'local' => 'Local'
    ],

    'general' => [
        'error' => 'internal server error, try again later.',
        'auth' => 'Las credenciales proporcionadas son incorrectas.',
        'logout' => 'La sesi??n se cerr?? correctamente.',
        'owner' => 'No eres due??o de la informaci??n',
        'forbidden' => 'su acci??n no est?? autorizada.',
    ],

    'meal-deals' => [
        'taken' => 'Esta oferta de comida ha sido tomada',
    ],

    'roles' => [
        'locked' => 'El rol est?? bloqueado'
    ],
];
