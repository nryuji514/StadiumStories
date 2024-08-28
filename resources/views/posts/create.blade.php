<!DOCTYPE HTML>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Posts</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }

            h1 {
                font-size: 2.5em;
                margin-bottom: 20px;
                color: #333;
            }

            form {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }

            input[type="text"],
            textarea {
                width: 100%;
                padding: 15px;
                font-size: 1.2em;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            select {
                width: 100%;
                padding: 15px;
                font-size: 1.2em;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            input[type="file"] {
                font-size: 1.2em;
                margin-bottom: 20px;
            }

            input[type="submit"] {
                background: #007bff;
                color: white;
                padding: 15px 30px;
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
                margin-top: 20px;
                text-align: center;
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
        </style>
    </head>
    <x-app-layout>
        <h1>Blog Name</h1>
        <form action="/posts" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="title">
                <h2>Title</h2>
                <input type="text" name="post[title]" placeholder="タイトル" value="{{ old('post.title') }}">
                <p class="title__error" style="color:red">{{ $errors->first('post.title') }}</p>
            </div>
            <div class="category">
                <h2>Category</h2>
                <select name="post[category_id]">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="body">
                <h2>Body</h2>
                <textarea name="post[body]" placeholder="今日も一日お疲れさまでした。">{{ old('post.body') }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('post.body') }}</p>
            </div>
            <div class="image">
                <label for="post_image"><h2>Image</h2></label>
                <input type="file" id="post_image" name="post_image[]" multiple>
                <p class="image__error" style="color:red">{{ $errors->first('post_image') }}</p>
            </div>
            <div class="image-preview" id="image-preview"></div>
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
            <input type="submit" value="store">
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </x-app-layout>
</html>
