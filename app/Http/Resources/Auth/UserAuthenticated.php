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
        $role = $this->getRoleNames()->first();

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->created_at,
            'role' => $role,
            'permissions' => $this->getAllPermissions()->pluck('name'),
        ];

        if ($role === RolesType::ADMIN_RESTAURANT) {
            $data['restaurantId'] = $this->restaurant->id;
        }

        return $data;
    }
}
