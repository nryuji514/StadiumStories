<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

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
