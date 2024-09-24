<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use Hasfactory;
        
    protected $fillable = [
        'name', 
        'address', 
        'place_id', 
        'latitude', 
        'longitude', 
        'route_id',
        'google_place_id', // Google Place APIからの識別子
        'photo_url',
         'type',
        ];
    

    // Routeモデルとのリレーション
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}