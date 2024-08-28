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
                <a href="/posts/{{ $post->id }}">
                    <h2 class="title">{{ $post->title }}</h2>
                </a>
                @foreach($post->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post Image" style="max-width:100%;">
                @endforeach
                <div class="category">
                    <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
                </div>
                <p class='body'>{{ $post->body }}</p>
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
