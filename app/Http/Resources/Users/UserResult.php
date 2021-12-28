<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResult extends JsonResource
{
    public function toArray($request)
    {
        $role = $this->roles()->first();

        $data = [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->full_name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'enable' => $this->enable,
            'createdAt' => $this->created_at,
            'role' => $role->name,
            'roleName' => $role->display,
            'permissions' => $this->getAllPermissions()->map(function ($item) {
                return [
                    'id' => (int) $item->id,
                    'name' => $item->name,
                    'display' => $item->display,
                    'module' => $item->module,
                ];
            }),
        ];

        if (!is_null($this->restaurant)) {
            $data['restaurant'] = [
                'id' => (int) $this->restaurant->id,
                'photo' => $this->restaurant->photo,
                'name' => $this->restaurant->name,
                'address' => $this->restaurant->address,
                'createdAt' => $this->restaurant->created_at,
            ];
        }

        return $data;
    }
}
