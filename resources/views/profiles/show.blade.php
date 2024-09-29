<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <!-- Styles -->
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
        }
        .profile-header img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            display: block;         
            margin: 0 auto;  
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            font-size: 1.1em;
            margin: 10px 0;
        }
        .bio {
            margin-top: 10px;
        }
        .edit-profile {
            margin-top: 20px;
            text-align: center;
        }
        .edit-profile a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.1em;
        }
    </style>
</head>
<x-app-layout>
    <div class="container">
        <div class="profile-header">
            <img src="{{ $profile->profile_picture_url ? asset('storage/' . $profile->profile_picture_url) : asset('images/default-image-url.jpg') }}" alt="Profile Picture">
            <p><strong>ニックネーム:</strong> {{ $profile->nickname ?? 'N/A' }}</p>
        </div>
        <div class="profile-info">
            <p class="bio"><strong>自己紹介:</strong> {{ $profile->bio ?? 'No bio available' }}</p>
        </div>
        <div class="edit-profile">
            <a href="{{ route('profiles.edit', $profile->id) }}">編集</a>
        </div>
        <div class="footer">
            <a href="{{ route('routes.index') }}">戻る</a>
        </div>
    </div>
</x-app-layout>
</html>
