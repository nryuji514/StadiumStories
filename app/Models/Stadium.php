<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;
    // テーブル名を指定
    protected $table = 'stadiums';

    protected $fillable = ['name', 'latitude', 'longitude'];
    
    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}