<!DOCTYPE HTML>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $post->title }} - Blog</title>
    
    <!-- Font AwesomeのCDN追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- 他のスタイルやメタタグ -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
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
            position: relative; /* 子要素の絶対位置を設定するための相対位置 */
        }
        .back-button {
            position: absolute; /* 画面の左上に配置 */
            top: 10px;
            left: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px; /* アイコンのサイズ */
            color: #007bff; /* 色の調整 */
        }
        .title {
            margin: 10px 0;
            font-size: 2em;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .content {
            margin: 20px 0;
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
        .btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #0056b3;
        }
        .likes {
            display: flex;
            justify-content: center; /* 中央に配置 */
            margin-top: 20px; /* 上部に余白を追加 */
        }
        .like-button {
            background-color: #ff5252; /* 背景色を目立たせる */
            color: white;
            font-size: 24px; /* 大きさを変更 */
            padding: 10px 20px; /* 内側の余白を追加 */
            border-radius: 50px; /* 丸みを持たせる */
            border: none;
            cursor: pointer;
            transition: background 0.3s ease; /* 背景色の変化にアニメーションを追加 */
            display: flex;
            align-items: center;
            margin: 0 10px; /* ボタンの間隔を調整 */
        }
        .like-button.liked {
            background-color: red; /* いいねされた時の背景色 */
        }
        .heart {
            margin-right: 10px; /* ハートとテキストの間にスペースを追加 */
        }
        .comments {
            font-size: 20px;
            margin-top: 30px;
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
            width: 100%; /* フル幅にする */
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
        /* ナビゲーションスタイル */
        nav {
            margin-bottom: 20px; /* ナビゲーションの下にマージンを追加 */
        }

        nav a {
            font-size: 18px; /* ナビゲーションリンクのフォントサイズ */
            color: #007bff; /* ナビゲーションリンクの色 */
            text-decoration: none; /* 下線を消す */
            margin-right: 20px; /* リンクの間隔 */
        }

        nav a:hover {
            text-decoration: underline; /* ホバー時に下線を追加 */
        }
    </style>
</head>
<x-app-layout>
    <div class="container">
        <nav>
            <a href="{{ route('stores.posts.index', ['store' => $data->id]) }}">＜ 戻る</a>
         </nav>
        
        <h1 class="title">{{ $post->title }}</h1>
        <div class="content">
            <div class="content__post">
                <p class="body">{{ $post->body }}</p>
                <div class="images">
                    @foreach($post->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="投稿画像">
                    @endforeach
                </div>
            </div>
        </div>
        <div class="likes">
            <button type="button" class="like-button {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                <span class="heart {{ $post->likes()->where('user_id', auth()->id())->exists() ? 'liked' : '' }}">♡</span>
                <span class="like-count">{{ $post->likes->count() }}</span> 
            </button>
        </div>

        <!-- コメントの表示 -->
        <div class="comments">
            <h2>コメント</h2>
            @if($post->comments->isEmpty())
                <p>まだコメントはありません・・</p>
            @else
                @foreach($post->comments as $comment)
                    <div class="comment">
                        <p><strong>{{ $post->nickname }}</strong></p>
                        <p>{{ $comment->comment }}</p>
                        <p><small>Posted at {{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: transparent; color: red; border: none; padding: 5px 10px; cursor: pointer;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                @endforeach
            @endif
        </div>
        <!-- コメントの投稿フォーム -->
        @auth
            <form action="{{ route('stores.posts.comments.store', ['store' => $data->id ,'post' => $post->id]) }}" method="POST">
                @csrf
                <textarea name="comment" placeholder="コメントを入力..."></textarea>
                <input type="submit" value="コメントを投稿">
            </form>
        @endauth
        
    </div>
    <script>
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
              .catch(error => console.error('Error:', error));
        });
    });
});
</script>
</x-app-layout>
</html>
