<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Permissions\PermissionDetail;
use App\Http\Resources\Permissions\PermissionsPaginated;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index() 
    {
        $query = Permission::orderBy('module', 'asc');

        return PermissionDetail::collection($query->get());
    }
}
