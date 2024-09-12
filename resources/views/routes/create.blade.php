<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>経路の追加</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
</head>
<body>
    <div class="container">
        <h1>駅から球場までの経路を追加</h1>
        <form action="{{ route('routes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="stadium">球場</label>
                <select id="stadium_id" name="stadium_id" class="form-control" required>
                    @foreach($stadiums as $stadium)
                        <option value="{{ $stadium->id }}" data-lat="{{ $stadium->latitude }}" data-lng="{{ $stadium->longitude }}">{{ $stadium->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="station_name">駅名</label>
                <input type="text" id="station_name" name="station_name" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">経路を保存</button>
            </div>
            <a href="{{ route('routes.index') }}">戻る</a>
        </form>
        <!-- 経路を表示する地図 -->
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <script>
        let map;
        let geocoder;
        let directionsService;
        let directionsRenderer;
        let stadiumMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 35.6895, lng: 139.6917 }, // 東京をデフォルトの中心に設定
                zoom: 14
            });
            geocoder = new google.maps.Geocoder();
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            // 初期表示時に選択された球場の位置を地図の中心に設定
            document.getElementById('stadium_id').addEventListener('change', setStadiumCenter);
            document.getElementById('station_name').addEventListener('input', codeAddress);

            // ページロード時に最初の球場を地図の中心に設定
            setStadiumCenter();
        }

        function setStadiumCenter() {
            const stadiumSelect = document.getElementById('stadium_id');
            const selectedOption = stadiumSelect.options[stadiumSelect.selectedIndex];
            const stadiumLat = parseFloat(selectedOption.getAttribute('data-lat'));
            const stadiumLng = parseFloat(selectedOption.getAttribute('data-lng'));
            const stadiumPosition = new google.maps.LatLng(stadiumLat, stadiumLng);

            map.setCenter(stadiumPosition);

            if (stadiumMarker) {
                stadiumMarker.setMap(null);
            }

            stadiumMarker = new google.maps.Marker({
                map: map,
                position: stadiumPosition,
                label: '選択した球場'
            });
        }

        function codeAddress() {
            const stationName = document.getElementById('station_name').value;
            if (stationName) {
                geocoder.geocode({ 'address': stationName + ', 日本' }, function (results, status) {
                    if (status === 'OK') {
                        const stationPosition = results[0].geometry.location;

                        // 選択された球場の位置を取得
                        const stadiumSelect = document.getElementById('stadium_id');
                        const selectedOption = stadiumSelect.options[stadiumSelect.selectedIndex];
                        const stadiumLat = parseFloat(selectedOption.getAttribute('data-lat'));
                        const stadiumLng = parseFloat(selectedOption.getAttribute('data-lng'));
                        const stadiumPosition = new google.maps.LatLng(stadiumLat, stadiumLng);

                        // 経路リクエストを作成
                        const request = {
                            origin: stationPosition,
                            destination: stadiumPosition,
                            travelMode: google.maps.TravelMode.DRIVING
                        };

                        directionsService.route(request, function (result, status) {
                            if (status === google.maps.DirectionsStatus.OK) {
                                directionsRenderer.setDirections(result);
                            } else {
                                alert('経路の取得に失敗しました: ' + status);
                            }
                        });
                    } else {
                        alert('ジオコーディングに失敗しました: ' + status);
                    }
                });
            }
        }
    </script>
</body>
</html>
