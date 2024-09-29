<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $store->name }} の投稿一覧</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            font-size: 1.2em;
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .post {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            overflow: hidden;
            padding: 10px;
        }
        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .post-author {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .profile-picture {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }
        .post-content {
            padding: 10px;
        }
        .post-content img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .post-title {
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .post-title:hover {
            text-decoration: underline;
        }
        .likes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .like-button {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
        }

        .like-button.liked {
            color: red;
        }

        .delete-button {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 24px;
            margin-left: 10px;
        }

        .delete-button:hover {
            color: darkred;
    }
        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
            color: #333;
        }
        .paginate {
            text-align: center;
            margin-top: 20px;
        }
        .like-button {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
        }
        .like-button.liked {
            color: red;
        }
         /* 投稿作成リンクのスタイル */
        .create-post-button {
            display: block;
            text-align: center;
            font-weight: bold;
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
            transition: background 0.3s;
        }
        .create-post-button:hover {
            background-color: #218838; /* ダークグリーンに変更 */
        }
    </style>
</head>
<x-app-layout>
    <header>
        <h1>{{ $data->name }} の投稿一覧</h1>
    </header>
    <a href="{{ route('stores.posts.create', ['store' => $data->id]) }}" class="create-post-button">
        {{ $data->name }}に対して投稿を作成する
    </a>
    <div class="container">
        <div class='posts'>
            @foreach($posts as $post)
                <div class='post'>
                    <div class="post-author">
                        <a href="{{ route('profiles.show', $post->user_id) }}">
                            @if ($post->user && $post->user->profile && $post->user->profile->profile_picture_url)
                                <img src="{{ asset('storage/' . $post->user->profile->profile_picture_url) }}" alt="Profile Picture" class="profile-picture">
                            @else
                                <img src="default-profile-picture.jpg" alt="Default Profile Picture" class="profile-picture">
                            @endif
                        </a>
                        <span><strong>"{{ $post->nickname }}"</strong></span>
                        <span><strong> Posted at {{ $post->created_at->format('Y-m-d H:i') }}</span></strong>
                    </div>
                    <div class="post-content">
                        @foreach($post->images as $image)
                            <a href="{{ route('stores.posts.show', ['store' => $store->id, 'post' => $post->id]) }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post Image">
                            </a>
                        @endforeach
                        <a href="{{ route('stores.posts.show', ['store' => $data->id, 'post' => $post->id]) }}" class="post-title">{{ $post->title }}</a>
                        <p>{{ $post->body }}</p>
                    </div>
                   <div class="likes">
                        <!-- いいねボタン -->
                        <button type="button" class="like-button {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                            <span class="heart {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'liked' : '' }}">♡</span>
                            <span class="like-count">{{ $post->likes->count() }}</span> 
                        </button>

                        <!-- ゴミ箱ボタン -->
                        <form action="{{ route('stores.posts.destroy', ['post' => $post->id, 'store' => $data->id]) }}" method="post" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('投稿を削除してもよろしいですか?')" class="delete-button">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
            @endforeach
        </div>

        <div class='paginate'>{{ $posts->links() }}</div>
        
    </div>

    <script>
        function deletePost(id) {
            'use strict';
            if (confirm('削除すると復元できません。\n本当によろしいですか？')) {
                document.querySelector(`#form_${id}`).submit();
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.like-button').forEach(button => {
                button.addEventListener('click', function () {
                    const postId = this.dataset.postId;
                    const isLiked = this.classList.contains('liked');
                    const url = isLiked
                        ? `/posts/${postId}/unlike`
                        : `/posts/${postId}/like`;

                    const method = isLiked ? 'DELETE' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              const likeCount = this.querySelector('.like-count');
                              likeCount.textContent = data.likes_count;
                              this.classList.toggle('liked');
                          }
                      })
                      .catch(error => console.error('Error:',));
                      });
            });
        });
</script>
</x-app-layout>
</html>