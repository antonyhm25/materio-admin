<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealDeal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'folio', 
        'price', 
        'new_price', 
        'available', 
        'meal_id'
    ];

    public function meal() 
    {
        return $this->belongsTo(Meal::class);
    }

    public function users() 
    {
        return $this->belongsToMany(User::class);
    }
}
