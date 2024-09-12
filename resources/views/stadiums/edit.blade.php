<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>球場を削除</title>
</head>
<body>
    <div class="container">
        <h1>経路一覧</h1>
        <a href="{{ route('routes.create') }}" class="btn btn-primary">新しい経路を追加</a>

        <ul>
            @foreach ($routes as $route)
                <li>
                    <a href="{{ route('routes.show', $route->id) }}">{{ $route->stadium->name }} から {{ $route->station->name }} まで</a>
                    <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-sm btn-warning">編集</a>
                    <form action="{{ route('routes.destroy', $route->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
             </li>
            @endforeach
        </ul>
        <a href="{{ route('stadiums.index') }}" class="btn btn-primary">戻る</a>
    </div>
</body>  
</html>
