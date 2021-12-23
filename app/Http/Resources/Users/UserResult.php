<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResult extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'enable' => $this->enable,
            'createdAt' => $this->created_at,
            'role' => $this->getRoleNames()->first(),
            'permissions' => $this->getAllPermissions()->map(function ($item) {
                return [
                    'id' => (int) $item->id,
                    'name' => $item->name,
                    'display' => $item->display,
                    'module' => $item->module,
                ];
            })
        ];
    }
}
