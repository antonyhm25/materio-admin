<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'user_id'];

    public function meals() 
    {
        return $this->hasMany(Meal::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
