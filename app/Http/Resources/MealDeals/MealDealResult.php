<?php

namespace App\Http\Resources\MealDeals;

use Illuminate\Http\Resources\Json\JsonResource;

class MealDealResult extends JsonResource
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
            'folio' => $this->folio,
            'name' => $this->meal->name,
            'restaurant' => $this->meal->restaurant->name,
            'price' => $this->price,
            'newPrice' => $this->new_price,
            'amount' => $this->amount,
            'available' => $this->available,
            'status' => $this->status,
            'mealId' => $this->meal_id,
            'createdAt' => $this->created_at,
        ];
    }
}
