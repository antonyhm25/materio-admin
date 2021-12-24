<?php

namespace App\Http\Resources\Meals;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MealPaginated extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->resource;
    }

    public $collects = MealItemPaginate::class;
}
