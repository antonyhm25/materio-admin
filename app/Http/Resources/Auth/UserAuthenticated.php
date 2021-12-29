<?php

namespace App\Http\Resources\Auth;

use App\Helpers\RolesType;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthenticated extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role = $this->roles()->first();

        $data = [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->full_name,
            'email' => $this->email,
            'createdAt' => $this->created_at,
            'role' => $role->name,
            'roleDisplay' => $role->display,
            'permissions' => $this->getAllPermissions()->pluck('name'),
        ];

        if (!is_null($this->restaurant)) {
            $data['restaurant'] = [
                'id' => (int) $this->restaurant->id,
                'name' => $this->restaurant->name,
                'photo' => $this->restaurant->photo,
                'address' => $this->restaurant->address,
            ];
        }

        return $data;
    }
}
