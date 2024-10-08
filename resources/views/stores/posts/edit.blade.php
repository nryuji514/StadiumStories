<!DOCTYPE HTML>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Posts</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <x-app-layout>
        <h1>Blog Name</h1>
        <form action="/posts/{{ $post->id }}" method="POST">
            <!--@csrfはFormタグを使用する際は必須-->
            @csrf
            @method('PUT')
            <div class="title">
                <h2>Title</h2>
                <!--placeholder="タイトル"でユーザーの入力を容易に-->
                <input type="text" name="post[title]" placeholder="タイトル" value={{ $post->title }}>
                <p class="title__error" style="color:red">{{ $errors->first('post.title') }}</p>
            </div>
            <div class="body">
                <h2>Body</h2>
                <!--textareaで長い入力フォームの作成-->
                <textarea name="post[body]" placeholder="今日も一日お疲れさまでした。">{{ $post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('post.body')}}</p>
            </div>
            <div class="post_image">
                <h2>Post Image</h2>
                <input type="file" name="post_image">
            </div>
            <input type="submit" value="update">
        </form>
        <a href="{{ route('stores.posts.create', ['store' => $data->id]) }}" style="display: block; text-align: center; margin-top: 20px; font-weight: bold;">投稿一覧へ</a>
    </x-app-layout>
</html>