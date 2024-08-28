<!DOCTYPE HTML>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ $post->title }} - Blog</title>
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
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            margin: 0;
            font-size: 2em;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .content {
            margin: 20px 0;
        }
        .content__post h3 {
            margin-top: 0;
            color: #444;
        }
        .category-link {
            color: #007bff;
            text-decoration: none;
        }
        .category-link:hover {
            text-decoration: underline;
        }
        .body {
            margin-top: 10px;
            font-size: 1.2em;
            line-height: 1.6;
            color: #555;
        }
        .images {
            margin-top: 20px;
        }
        .images img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .edit a {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .edit a:hover {
            background: #0056b3;
        }
        .comments {
            margin-top: 30px;
        }
        .comments h2 {
            margin-top: 0;
        }
        .comment {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .comment p {
            margin: 0;
        }
        .comment small {
            color: #999;
        }
        form {
            margin-top: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">{{ $post->title }}</h1>
        <div class="content">
            <div class="content__post">
                <h3>本文</h3>
                <a class="category-link" href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
                <p class="body">{{ $post->body }}</p>
                <div class="images">
                    @foreach($post->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="投稿画像">
                    @endforeach
                </div>
            </div>
        </div>
        <div class="edit">
            <a href="/posts/{{ $post->id }}/edit">Edit</a>
        </div>
        <!-- コメントの表示 -->
        <div class="comments">
            <h2>Comments</h2>
            @if($post->comments->isEmpty())
                <p>No comments yet.</p>
            @else
                @foreach($post->comments as $comment)
                    <div class="comment">
                        <p><strong>{{ $comment->user->name }}</strong></p>
                        <p>{{ $comment->comment }}</p>
                        <p><small>Posted at {{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 5px;">Delete</button>
                            </form>
                        @endcan
                    </div>
                @endforeach
            @endif
        </div>
        <!-- コメントの投稿フォーム -->
        @auth
            <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST">
                @csrf
                <textarea name="comment" placeholder="コメントを入力..."></textarea>
                <input type="submit" value="コメントを投稿">
            </form>
        @endauth
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </div>
</body>
</html>
