<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserPaginated extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->resource;
    }
}


