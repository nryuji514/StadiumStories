<!--<!DOCTYPE html>-->
<!--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">-->
<!--<head>-->
<!--    <meta charset="utf-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <title>Profile</title>-->
    <!-- Fonts -->
<!--    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">-->
    <!-- Styles -->
<!--    <style>-->
<!--        body {-->
<!--            font-family: 'Nunito', sans-serif;-->
<!--            margin: 0;-->
<!--            padding: 0;-->
<!--            background-color: #f4f4f4;-->
<!--        }-->
<!--        .container {-->
<!--            max-width: 800px;-->
<!--            margin: 20px auto;-->
<!--            padding: 10px;-->
<!--            background: white;-->
<!--            border-radius: 8px;-->
<!--            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);-->
<!--        }-->
<!--        .profile-header {-->
<!--            text-align: center;-->
<!--            margin-bottom: 20px;-->
<!--        }-->
<!--        .profile-header img {-->
<!--            border-radius: 50%;-->
<!--            max-width: 150px;-->
<!--            height: auto;-->
<!--        }-->
<!--        .profile-info {-->
<!--            margin-bottom: 20px;-->
<!--            text-align: center;-->
<!--        }-->
<!--        .profile-info h2 {-->
<!--            margin: 10px 0;-->
<!--            color: #333;-->
<!--        }-->
<!--        .profile-info p {-->
<!--            color: #666;-->
<!--            font-size: 1em;-->
<!--        }-->
<!--        .profile-edit {-->
<!--            text-align: center;-->
<!--            margin-top: 20px;-->
<!--        }-->
<!--        .profile-edit a {-->
<!--            background-color: #007BFF;-->
<!--            color: white;-->
<!--            padding: 10px 20px;-->
<!--            border-radius: 5px;-->
<!--            text-decoration: none;-->
<!--        }-->
<!--    </style>-->
<!--</head>-->
<!--<x-app-layout>-->
<!--    <div class="container">-->
<!--        <div class="profile-header">-->
<!--            @if($profile->profile_picture_url)-->
<!--                <img src="{{ asset('storage/' . $profile->profile_picture_url) }}" alt="{{ $profile->nickname }}'s Profile Picture">-->
<!--            @else-->
<!--                <img src="{{ asset('storage/default_profile.png') }}" alt="Default Profile Picture">-->
<!--            @endif-->
<!--        </div>-->
<!--        <div class="profile-info">-->
<!--            <h2>{{ $profile->nickname }}</h2>-->
<!--            <p>{{ $profile->bio }}</p>-->
<!--        </div>-->
<!--        <div class="profile-edit">-->
<!--            <a href="{{ route('profiles.edit', $profile->id) }}">Edit Profile</a>-->
<!--        </div>-->
<!--    </div>-->
<!--</x-app-layout>-->
<!--</html>-->
