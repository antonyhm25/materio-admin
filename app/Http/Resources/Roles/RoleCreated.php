<?php

namespace App\Http\Resources\Roles;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleCreated extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'display' => $this->display,
            'locked' => (bool) $this->locked,
            'permissions' => $this->permissions->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            })
        ];
    }
}
