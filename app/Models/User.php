<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use hasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'family',
        'email',
        'password',
        'phone',
        'active',
        'level',
        'status',
        'user_name',
        'cell_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function product()
    {
        return $this->hasMany(Products::class);
    }
    public function siteDetails()
    {
        return $this->hasMany(SiteDetails::class);
    }
    public function menu()
    {
        return $this->hasMany(Menu::class);
    }
    public function productCategories()
    {
        return $this->hasMany(ProductCategories::class);
    }
    public function slider()
    {
        return $this->hasMany(Slider::class);
    }

    public function isAdmin()
    {
        return $this->level=='admin'?true:false;
    }
    public function isBuyer()
    {
        return $this->level=='buyer'?true:false;
    }

    public function webPages()
    {
        return $this->hasMany(WebPages::class);
    }
    public function menuCategories()
    {
        return $this->hasMany(MenuCategories::class);
    }

    public function factory()
    {
        return $this->hasMany(Factories::class);
    }
    public function size()
    {
        return $this->hasMany(Sizes::class);
    }
    public function standard()
    {
        return $this->hasMany(Standards::class);
    }

}
