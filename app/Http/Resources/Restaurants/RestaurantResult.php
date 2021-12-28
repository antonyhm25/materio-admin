<?php

namespace App\Http\Resources\Restaurants;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResult extends JsonResource
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
            'id' => $this->id,
            'name' => $this->full_name,
            'email' => $this->email,
            'enable' => $this->enable,
            'createdAt' => $this->created_at,
            'restaurant' => [
                'id' => (int) $this->restaurant->id,
                'name' => $this->restaurant->name,
                'address' => $this->restaurant->address,
                'createdAt' => $this->restaurant->created_at,
            ],
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
