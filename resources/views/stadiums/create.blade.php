<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>球場を追加</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
    <script>
        let map;
        let geocoder;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 35.6895, lng: 139.6917 }, // 東京をデフォルトの中心に設定
                zoom: 8
            });
            geocoder = new google.maps.Geocoder();
        }

        function codeAddress() {
            const address = document.getElementById('name').value;
            geocoder.geocode({ 'address': address }, function (results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                    new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                    // 緯度と経度を隠しフィールドに設定
                    document.getElementById('latitude').value = location.lat();
                    document.getElementById('longitude').value = location.lng();
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            document.getElementById('name').addEventListener('input', codeAddress);
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>新規球場追加</h1>

        <form action="{{ route('stadiums.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">球場名</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">球場を追加する</button>
        </form>

        <!-- 球場の位置を表示する地図 -->
        <div id="map" style="height: 400px; width: 100%;"></div>
        
        <a href="{{ route('stadiums.index') }}" class="btn btn-primary">戻る</a>
    </div>
</body>
</html>
