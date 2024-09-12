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

    <a href="{{ route('routes.index') }}" class="btn btn-primary">経路一覧を見る</a>
</body>
</html>
