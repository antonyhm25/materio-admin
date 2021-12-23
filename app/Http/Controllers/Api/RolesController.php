<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Roles\RoleCreated;
use App\Http\Resources\Roles\RoleDetail;
use App\Http\Resources\Roles\RoleList;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function index() 
    {
        try {
            $query = Role::orderBy('name', 'asc');

            return RoleList::collection($query->get());
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function show(Role $role) 
    {
        try {
            return new RoleDetail($role);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

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

    public function update(Request $request, Role $role) 
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($role->id),
                'alpha_dash'
            ],
            'display' => 'required|string',
            'locked' => 'required|in:0,1',
            'permissions' => 'required|array',
        ]);

        try {
            $role->fill([
                'name' => strtolower($request->name),
                'display' => $request->display,
                'locked' => $request->locked,
            ]);

            $role->save();

            $role->givePermissionTo($request->permissions);

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroy(Role $role) 
    {
        try {
            $role->delete();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
