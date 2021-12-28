<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserItemPaginate extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'enable' => $this->enable,
            'role' => $this->roles()->pluck('display')->first(),
            'createdAt' => $this->created_at,
        ];
    }
}
