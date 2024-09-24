<!-- resources/views/routes/index.blade.php -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>経路一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        h1 {
            margin: 0;
            color: #007bff;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-warning {
            background-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .stadium-group {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .stadium-name {
            font-size: 1.5em;
            margin-bottom: 10px;
            font-weight: bold;
            color: #343a40;
        }
        .route-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            background-color: #fff;
        }
        .route-item a {
            color: #007bff;
            text-decoration: none;
        }
        .route-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<x-app-layout>
    <div class="container">
        <div class="header">
            <h1>経路一覧</h1>
            <div>
                <a href="{{ route('stadiums.create') }}" class="btn btn-success">新しい球場を追加</a>
                <a href="{{ route('routes.create') }}" class="btn btn-primary">新しい経路を追加</a>
            </div>
        </div>

        @foreach ($stadiums as $stadium)
            <div class="stadium-group">
                <div class="stadium-name">{{ $stadium->name }}</div>
                <ul>
                    @forelse ($stadium->routes as $route)
                        <li class="route-item">
                            <a href="{{ route('routes.show', $route->id) }}">
                                {{ $route->station_name }} から {{ $route->stadium->name }} まで
                            </a>
                            <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-sm btn-warning">編集</a>
                            <form action="{{ route('routes.destroy', $route->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                        </li>
                    @empty
                        <li class="route-item">この球場には経路が登録されていません。</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    </div>

    <div class="container">
        <a href="{{ route('stadiums.index') }}" class="btn btn-primary">球場一覧を見る</a>
    </div>
</x-app-layout>
</html>
