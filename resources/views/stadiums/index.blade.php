<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>球場一覧</title>
</head>

<x-app-layout>
    <div class="container">
        <h1>球場一覧</h1>
       
        <ul>
            @foreach ($stadiums as $stadium)
                <li>
                    <a href="{{ route('stadiums.show', $stadium->id) }}">{{ $stadium->name }}</a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('stadiums.create') }}" class="btn btn-success">新しい球場を追加</a>
        <a href="{{ route('routes.index') }}" class="btn btn-primary">経路一覧を見る</a>
    </div>
</x-app-layout>
</html>