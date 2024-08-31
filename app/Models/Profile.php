<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id','nickname','profile_picture_url','bio'];

    // リレーション: Profile belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($profile) {
            if (Profile::where('user_id', $profile->user_id)->exists()) {
                throw new \Exception('A profile for this user already exists.');
            }
        });
    }
    
}
