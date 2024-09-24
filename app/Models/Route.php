<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'station_name',
        'stadium_id',
        'route_data',
        'latitude',
        'longitude',
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
