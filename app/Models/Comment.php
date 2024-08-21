<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
    ];


    // リレーション: Comment belongs to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // リレーション: Comment belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
