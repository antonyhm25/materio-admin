<?php

namespace App\Http\Resources\Permissions;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionDetail extends JsonResource
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
            'module' => $this->module,
        ];
    }
}
