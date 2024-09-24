<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>経路表示</title>
    <style>
        #map {
            height: 450px;
            width: 100%;
        }
        .store-list {
            margin-top: 20px;
        }
        .store-container {
            display: flex; /* フレックスボックスを使用 */
            flex-wrap: wrap; /* ラップさせて複数行にする */
            justify-content: space-between; /* カードの間隔を均等に */
        }
        .store-card {
            flex: 1 1 200px; /* 幅を指定（最小200px） */
            margin: 10px; /* カードの周りにマージンを追加 */
            padding: 15px;
            border: 1px solid #ccc; /* ボーダーを追加 */
            border-radius: 5px; /* 角を丸める */
            text-align: center; /* テキストを中央揃え */
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); /* シャドウを追加 */
            background-color: #fff; /* 背景色を白に */
        }
        .store-card img {
            max-width: 100%; /* 画像の最大幅をカードに合わせる */
            height: auto; /* 高さを自動調整 */
            border-radius: 5px; /* 画像も角を丸める */
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
        .btn-primary {
            background-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
</head>
<body>
    <h1>経路表示</h1>

    <h2>球場: {{ $route->stadium->name }}</h2>

    <h2>{{ $route->station_name }} からの経路</h2>

    <!-- Google Mapsを表示するための要素 -->
    <div id="map"></div>
    
    <h2>この経路にある店舗</h2>
    @if($route->stores->isEmpty())
        <p>この経路には店舗がありません。</p>
    @else
        <div class="store-list">
            <div class="store-container">
                @foreach ($route->stores as $store)
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
    @endif

    <form action="{{ route('stores.searchAndSave', $route->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">店舗を自動取得して保存</button>
    </form>

    <script>
        function initMap() {
            // 地図の初期設定
            const stationLatLng = { lat: {{ $route->latitude }}, lng: {{ $route->longitude }} };
            const stadiumLatLng = { lat: {{ $route->stadium->latitude }}, lng: {{ $route->stadium->longitude }} };

            // 地図を作成
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: stationLatLng, // 初期中心位置
            });

            // DirectionsServiceとDirectionsRendererを使用して経路を描画
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            // 駅から球場までのルートをリクエスト
            const request = {
                origin: stationLatLng, // 出発地点
                destination: stadiumLatLng, // 到着地点
                travelMode: google.maps.TravelMode.DRIVING, // 移動手段（徒歩、車など）
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

    <a href="{{ route('routes.index', ['route' => $route->id]) }}" class="btn btn-primary">経路一覧を見る</a>
</body>
</html>
