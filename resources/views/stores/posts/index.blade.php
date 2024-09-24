<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $store->name }} の投稿一覧</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
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
        }
        .post:hover {
            transform: translateY(-5px);
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
            cursor: pointer; /* 画像にカーソルを追加 */
        }
        .post-title {
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 10px;
            cursor: pointer; /* タイトルにカーソルを追加 */
            color: #007bff; /* タイトルの色 */
        }
        .likes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .button-group button {
            background: #ff5252;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .button-group button:hover {
            background: #ff1744;
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
    </style>
</head>
<x-app-layout>
    {{ dd($store); }}
    <header>
        <h1>{{ $store->name }} の投稿一覧</h1>
    </header>
    <a href="{{ route('stores.posts.create', ['store' => $store->id]) }}" style="display: block; text-align: center; margin-top: 20px; font-weight: bold;">{{ $store->name }}に対して投稿を作成する</a>
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
                        <a href="{{ route('stores.posts.show', ['store' => $store->id, 'post' => $post->id]) }}" class="post-title">{{ $post->title }}</a>
                        <p>{{ $post->body }}</p>
                    </div>
                    <div class="likes">
                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="like-button {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'liked' : '' }}">
                                <i class="fa fa-heart"></i> {{ $post->likes->count() }} Likes
                            </button>
                        </form>
                        @if($post->likes()->where('user_id', auth()->id())->exists())
                            <form action="{{ route('posts.unlike', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="unlike-button">Unlike</button>
                            </form>
                        @endif
                    </div>
                    <div class="button-group">
                        <form action="{{ route('posts.destroy', ['post' => $post->id, 'store' => $store->id]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                        </form>
                    </div>
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
    </script>
</x-app-layout>
</html>
