<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'restaurant_id'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        if (is_null($search)) {
            return $query;
        }

        return $query->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%");
    }

    public function scopeByRestaurant(Builder $query, $restaurant)
    {
        if (is_null($restaurant)) {
            return $query;
        }

        return $query->where('restaurant_id', $restaurant);
    }
}
