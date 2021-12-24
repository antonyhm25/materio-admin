<?php

namespace App\Http\Resources\Meals;

use Illuminate\Http\Resources\Json\JsonResource;

class MealItemPaginate extends JsonResource
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
            'description' => $this->description,
            'restaurant' => $this->restaurant->name,
            'createdAt' => $this->created_at,
        ];
    }
}