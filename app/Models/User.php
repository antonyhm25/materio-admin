<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'password',
        'enable',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar', 'role'];

    public function getAvatarAttribute() 
    {
        return Gravatar::get($this->email);
    }

    public function getRoleAttribute() 
    {
        return $this->roles()->pluck('display')->first();
    }

    public function restaurant() 
    {
        return $this->hasOne(Restaurant::class);
    }

    public function mealDeals() 
    {
        return $this->hasMany(MealDeal::class);
    }
    
    public function scopeSearch(Builder $query, $search)
    {
        if (is_null($search)) {
            return $query;
        }

        return $query->where('full_name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
    }

    public function scopeByType(Builder $query, $type)
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
