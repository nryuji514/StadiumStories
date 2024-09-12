<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latitude', 'longitude'];


    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
