<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable= [
        'title',
        'body',
        'user_id',
        'store_id'
        ];

    // カスタムメソッド: ページネーション
    public function getPaginateByLimit(int $limit_count = 6)
    {
        return $this::orderBy('updated_at', 'DESC')->paginate($limit_count);
    }

    // リレーション: Post belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // リレーション: Post has many PostImages
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // リレーション: Post has many Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    // User モデル経由で Profile モデルにアクセスし、nickname を取得
    public function getNicknameAttribute()
    {
        return $this->user->profile->nickname ?? 'Default Nickname';
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

}
