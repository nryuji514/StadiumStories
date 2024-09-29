<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>経路表示</title>
    <style>
        /* コンテナのスタイル */
        body {
            font-family: 'Arial', sans-serif; /* フォントファミリーを設定 */
            background-color: #e9ecef; /* 背景色を少し明るく */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 30px; /* パディングを増やして余裕を持たせる */
            background-color: #ffffff; /* コンテナの背景色を設定 */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* シャドウを強くする */
        }

        /* ヘッダーのスタイル */
        h1 {
            font-size: 28px; /* フォントサイズを大きく */
            color: #343a40; /* タイトルの色 */
            margin-bottom: 15px; /* マージンを追加 */
        }

        h2 {
            font-size: 24px; /* サブタイトルのフォントサイズを大きく */
            color: #495057; /* サブタイトルの色 */
            margin: 20px 0; /* マージンを調整 */
        }

        /* 地図のスタイル */
        #map {
            height: 450px;
            width: 100%;
            border-radius: 5px; /* 地図の角を丸く */
            margin-bottom: 30px; /* マージンを追加 */
            border: 2px solid #007bff; /* 地図にボーダーを追加 */
        }

        .store-list {
            margin-top: 30px; /* 店舗リストの上部にマージンを追加 */
        }

        .store-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .store-card {
            flex: 1 1 200px;
            margin: 15px;
            padding: 15px;
            border: 1px solid #dee2e6; /* カードのボーダー */
            border-radius: 5px;
            text-align: center;
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.15);
            background-color: #f9f9f9; /* カードの背景色 */
            transition: transform 0.2s; /* ホバー時のトランジション */
        }

        .store-card:hover {
            transform: scale(1.05); /* ホバー時にカードを拡大 */
        }

        .store-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* ボタンのスタイル */
        .btn {
            display: inline-block;
            padding: 12px 25px; /* ボタンのパディングを増やす */
            margin: 5px;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            font-size: 18px; /* フォントサイズを調整 */
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s; /* ホバー時のトランジション */
        }

        .btn-primary {
            background-color: #007bff; /* プライマリボタンの色 */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* ホバー時の色 */
        }

        .btn-success {
            background-color: #28a745; /* 成功ボタンの色 */
        }

        .btn-success:hover {
            background-color: #218838; /* ホバー時の色 */
        }
        
        /* ナビゲーションスタイル */
        nav {
            margin-bottom: 20px; /* ナビゲーションの下にマージンを追加 */
        }

        nav a {
            font-size: 20px; /* ナビゲーションリンクのフォントサイズ */
            color: #007bff; /* ナビゲーションリンクの色 */
            text-decoration: none; /* 下線を消す */
            margin-right: 20px; /* リンクの間隔 */
        }

        nav a:hover {
            text-decoration: underline; /* ホバー時に下線を追加 */
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
</head>
<body>
    
    <!-- コンテナ開始 -->
    <div class="container">
        <!-- ナビゲーション -->
        <nav>
            <a href="{{ route('routes.index', ['route' => $route->id]) }}">＜ 戻る</a>
        </nav>
        
        <h1>経路表示</h1>
        <h2>球場: {{ $route->stadium->name }}</h2>
        <h2>{{ $route->station_name }} からの経路</h2>

        <!-- Google Mapsを表示するための要素 -->
        <div id="map"></div>
        
        <form action="{{ route('stores.searchAndSave', $route->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">店舗を自動取得して保存</button>
        </form>
        
       <h2>この経路にある店舗</h2>
        @if($route->stores->isEmpty())
            <p>この経路には店舗がありません。</p>
        @else
            <!-- 飲食店の表示 -->
            <h3>飲食店</h3>
            <div class="store-list">
                <div class="store-container">
                    @foreach ($route->stores->where('type', 'restaurant') as $store)
                        <div class="store-card">
                            <h2>{{ $store->name }}</h2>
                            @if ($store->photo_url)
                                <img src="{{ $store->photo_url }}" alt="{{ $store->name }}の写真">
                            @endif
                            <p>{{ $store->address }}</p>
                            <a href="{{ route('stores.posts.index', ['store' => $store->id]) }}">この店舗の投稿を表示</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- コンビニの表示 
            <h3>コンビニ</h3>
            <div class="store-list">
                <div class="store-container">
                    @foreach ($route->stores->where('type', 'convenience_store') as $store)
                        <div class="store-card">
                            <h2>{{ $store->name }}</h2>
                            @if ($store->photo_url)
                                <img src="{{ $store->photo_url }}" alt="{{ $store->name }}の写真">
                            @endif
                            <p>{{ $store->address }}</p>
                            <a href="{{ route('stores.posts.index', ['store' => $store->id]) }}">この店舗の投稿を表示</a>
                        </div>
                    @endforeach
                </div>
            </div>

           
            <h3>スーパー</h3>
            <div class="store-list">
                <div class="store-container">
                    @foreach ($route->stores->where('type', 'supermarket') as $store)
                        <div class="store-card">
                            <h2>{{ $store->name }}</h2>
                            @if ($store->photo_url)
                                <img src="{{ $store->photo_url }}" alt="{{ $store->name }}の写真">
                            @endif
                            <p>{{ $store->address }}</p>
                            <a href="{{ route('stores.posts.index', ['store' => $store->id]) }}">この店舗の投稿を表示</a>
                        </div>
                    @endforeach
                </div>
            </div>-->
        @endif
    </div>
    <!-- コンテナ終了 -->

    <script>
        function initMap() {
            const stationLatLng = { lat: {{ $route->latitude }}, lng: {{ $route->longitude }} };
            const stadiumLatLng = { lat: {{ $route->stadium->latitude }}, lng: {{ $route->stadium->longitude }} };

            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: stationLatLng,
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            const request = {
                origin: stationLatLng,
                destination: stadiumLatLng,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            directionsService.route(request, function(result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    alert('経路が見つかりませんでした。');
                }
            });
        }
    </script>
</body>
</html>
