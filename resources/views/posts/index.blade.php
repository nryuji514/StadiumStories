<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
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
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .post {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .post:hover {
            transform: translateY(-5px);
        }
        .title {
            color: #333;
            margin: 0;
        }
        .post-details {
            margin-bottom: 10px;
            color: #666;
            font-size: 0.9em;
        }
        .post-details .user-name {
            font-weight: bold;
        }
        .post-details .post-time {
            margin-left: 10px;
            color: #999;
        }
        .category {
            color: #999;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .body {
            margin-bottom: 10px;
            color: #666;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
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
        .paginate {
            text-align: center;
            margin-top: 20px;
        }
        .welcome-message {
            text-align: center;
            margin-top: 10px;
            font-size: 1.2em;
        }
        .likes {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .like-button, .unlike-button {
            background-color: transparent;
            border: none;
            color: #ff5252;
            cursor: pointer;
            font-size: 1em;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }
        .like-button.liked, .unlike-button {
            color: #e0245e;
        }
        .like-button .fa-heart {
            margin-right: 5px;
        }
        .like-button.liked .fa-heart {
            color: #e0245e;
            animation: pop 0.3s ease;
        }
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .post-author {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .profile-picture {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
        }

        .author-name {
        font-weight: bold;
    }

    </style>
</head>
<x-app-layout>
    <header>
        <h1>Blog Name</h1>
        <a href='/posts/create' style="color:white;">Create New Post</a>
    </header>

    <div class="container">
        <div class='posts'>
            @foreach($posts as $post)
            <div class='post'>
                <div class="post-author">
                    <a href="{{ route('profiles.show', $post->id) }}" class="post-author-link">
                        @if ($post->user && $post->user->profile && $post->user->profile->profile_picture_url)
                            <img src="{{ asset('storage/' . $post->user->profile->profile_picture_url) }}" alt="Profile Picture" class="profile-picture">
                        @else
                            <img src="default-profile-picture.jpg" alt="Default Profile Picture" class="profile-picture">
                        @endif
                    </a>
                    <span class="author-name"><strong>投稿者:</strong>{{ $post->nickname }}</span>
                    <span class="post-time">・・{{ $post->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <a href="{{ route('show', $post->id) }}" class="post-link">
                    <h2 class="post-title">{{ $post->title }}</h2>
                    @foreach($post->images as $image)
                     <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post Image" style="max-width:100%;">
                    @endforeach
                    <div class="category">
                        <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
                    </div>
                    <p class='body'>{{ $post->body }}</p>
                </a>
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
                    <form action="/posts/{{ $post->id }}" id="form_{{ $post->id }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="deletePost({{ $post->id }})">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class='paginate'>{{ $posts->links() }}</div>
        
        <div class="welcome-message">
            @if(Auth::check())
            <p>Welcome, {{ Auth::user()->name }}!</p>
            @else
            <p>Welcome, Guest!</p>
            @endif
        </div>
    </div>

    <script>
        function deletePost(id) {
            'use strict'
            if (confirm('削除すると復元できません。\n本当によろしいですか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</x-app-layout>
</html>
