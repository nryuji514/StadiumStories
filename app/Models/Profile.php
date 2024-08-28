<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    
    protected $fillable = ['profile_picture_url','bio'];

    // リレーション: Profile belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
