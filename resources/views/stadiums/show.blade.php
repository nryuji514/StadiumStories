<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $stadium->name }}の経路表示</title>
    <style>
        #map {
            height: 450px;
            width: 100%;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
</head>
<body>
    <h1>{{ $stadium->name }}の経路表示</h1>

    <h2>{{ $stadium->name }}</h2>

    <h2>経路</h2>
    <div id="map"></div>

    <script>
        function initMap() {
            // 地図の初期設定
            const stadiumLatLng = { lat: {{ $stadium->latitude }}, lng: {{ $stadium->longitude }} };

            // 地図を作成
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: stadiumLatLng, // 初期中心位置を球場に設定
            });

            // DirectionsServiceとDirectionsRendererを使用して経路を描画
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            @foreach($routes as $route)
                // 各経路をリクエスト
                const request = {
                    origin: { lat: {{ $route->latitude }}, lng: {{ $route->longitude }} }, // 駅の緯度・経度
                    destination: stadiumLatLng, // 球場の緯度・経度
                    travelMode: google.maps.TravelMode.DRIVING, // 移動手段
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        alert('経路が見つかりませんでした。');
                    }
                });
            @endforeach
        }
    </script>

    <a href="{{ route('stadiums.index') }}" class="btn btn-primary">球場一覧を見る</a>
</body>
</html>
