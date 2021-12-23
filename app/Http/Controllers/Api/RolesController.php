<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Roles\RoleCreated;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|unique:roles|alpha_dash',
            'display' => 'required|string',
            'locked' => 'required|in:0,1',
            'permissions' => 'required|array',
        ]);

        try {
            $role = Role::create([
                'name' => strtolower($request->name),
                'display' => $request->display,
                'locked' => $request->locked,
            ]);

            $role->givePermissionTo($request->permissions);

            return new RoleCreated($role);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}