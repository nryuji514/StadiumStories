<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
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
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="file"] {
            padding: 0;
        }
        .form-group .error {
            color: red;
        }
        .form-group .success {
            color: green;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<x-app-layout>
    <div class="container">
        <h1>プロフィール編集</h1>

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <form action="{{ route('profiles.update',['profile' => $profile->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nickname">ニックネーム</label>
                <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $profile->first_name) }}">
                @error('nickname')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="bio">自己紹介</label>
                <textarea id="bio" name="bio">{{ old('bio', $profile->bio ?? '') }}</textarea>
                @error('bio')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="profile_picture_url">プロフィール画像</label>
                <input type="file" id="profile_picture_url" name="profile_picture_url">
                @if($profile->profile_picture_url)
                    <div class="image-preview">
                        <img src="{{ asset('storage/' . $profile->profile_picture_url) }}" alt="Profile Picture" style="max-width: 200px; height: auto;">
                    </div>
                @endif
                @error('profile_picture_url')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit">保存する</button>
            </div>
        </form>
    </div>
</x-app-layout>
</html>
