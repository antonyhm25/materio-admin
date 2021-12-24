<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Permissions\PermissionDetail;

class PermissionsController extends Controller
{
    public function index() 
    {
        $this->authorize('viewAny', Permission::class);
        
        try {
            $query = Permission::orderBy('module', 'asc');

            return PermissionDetail::collection($query->get());
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
