<!DOCTYPE HTML>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>投稿作成</title>
    <!-- Font AwesomeのCDN追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 15px;
            font-size: 1.1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        textarea {
            height: 150px;
            resize: none;
        }

        input[type="file"] {
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        input[type="submit"] {
            background: #007bff;
            color: white;
            padding: 12px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .footer {
            font-size: 50px; /* アイコンのサイズ */
            color: #007bff; /* 色の調整 */
            margin-top: 0px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .image-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        .required {
            color: red;
            font-size: 0.9em;
        }
        .title {
            margin: 15px auto;
        }
        
    </style>
</head>
<x-app-layout>
    <form action="{{ route('stores.posts.store', ['store' => $data->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store_id" value="{{ $data->id }}">
        
        <div class="footer">
            <a href="{{ route('stores.posts.index', ['store' => $data->id]) }}"><</a>
        </div>
        
        <div class="title">
            <label for="title">タイトル <span class="required">必須</span></label>
            <input type="text" name="title" id="title" placeholder="タイトルを入力してください" value="{{ old('title') }}">
            <p class="title__error" style="color:red">{{ $errors->first('post.title') }}</p>
        </div>
        
        <div class="body">
            <label for="body">内容 <span class="required">必須</span></label>
            <textarea name="body" id="body" placeholder="内容を入力してください">{{ old('body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('post.body') }}</p>
        </div>
        
        <div class="image">
            <label for="post_image">画像のアップロード</label>
            <input type="file" id="post_image" name="post_image[]" multiple>
            <p class="image__error" style="color:red">{{ $errors->first('post_image') }}</p>
        </div>
        
        <div class="image-preview" id="image-preview"></div>
        
        <input type="submit" value="投稿する">
    </form>
    
    

    <script>
        document.querySelector('input[name="post_image"]').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Image preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
</html>
