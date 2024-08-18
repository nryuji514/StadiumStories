<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
    </head>
    <x-app-layout>
        <h1>Blog Name</h1>
        <a href='/posts/create'>create</a>
        <div class='posts'>
            @foreach($posts as $post)
                <div class='post'>
                    <small>{{ $post->user->name }}</small>
                    <a href = "/posts/{{ $post->id }}"><h2 class="title">{{$post->title}}</h2></a>
                    <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
                    <p class='body'>{{ $post->body }}</p>
                    <form action="/posts/ {{ $post->id }}" id="form_{{ $post->id }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="deletePost({{ $post->id }})">delete</button>
                    </form>
                </div>
            @endforeach
            @if(Auth::check())
                <p>Welcome, {{ Auth::user()->name }}!</p>
            @else
                <p>Welcome, Guest!</p>
            @endif
        </div>
        <div class='paginate'>{{ $posts->links() }}</div>
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