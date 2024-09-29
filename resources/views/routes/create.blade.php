<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>経路の追加</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0px;
    }
    .container {
        max-width: 800px;  /* 最大幅を800pxに設定 */
        width: 90%;  /* 親要素に対して全体の幅を確保 */
        margin: 20px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    h1 {
        font-family: 'Arial Black', sans-serif;
        font-size: 36px;
        color: #0056b3;
        text-align: center;
        letter-spacing: 2px;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-control {
        width: 100%;  /* フォームの幅を100%に設定 */
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;/* パディングやボーダーを含んだ幅を指定 */
    }
    .btn-primary {
        display: inline-block;
        width: 100%;  /* ボタンも幅を100%に */
        padding: 10px;
        margin-top: 10px;
        background-color: #007bff;
        color: white;
        text-align: center;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    #map {
        height: 400px;
        width: 100%;  /* マップもコンテナに合わせた幅に調整 */
        margin-top: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    nav {
        margin-bottom: 20px;
    }
    nav a {
        font-size: 18px;
        color: #007bff;
        text-decoration: none;
    }
    nav a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>

    <div class="container">
        <nav>
            <a href="{{ route('routes.index') }}">＜ 戻る</a>
        </nav>
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

            <div class="form-group">
                <label for="station_name">駅名</label>
                <input type="text" id="station_name" name="station_name" class="form-control" required>
            </div>

            
            <button type="submit" class="btn btn-primary">経路を保存</button>
            
        </form>

        <div id="map"></div>
    </div>

    <script>
        let map;
        let geocoder;
        let directionsService;
        let directionsRenderer;
        let stadiumMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 35.6895, lng: 139.6917 },
                zoom: 14
            });
            geocoder = new google.maps.Geocoder();
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            document.getElementById('stadium_id').addEventListener('change', setStadiumCenter);
            document.getElementById('station_name').addEventListener('input', codeAddress);

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

                        const stadiumSelect = document.getElementById('stadium_id');
                        const selectedOption = stadiumSelect.options[stadiumSelect.selectedIndex];
                        const stadiumLat = parseFloat(selectedOption.getAttribute('data-lat'));
                        const stadiumLng = parseFloat(selectedOption.getAttribute('data-lng'));
                        const stadiumPosition = new google.maps.LatLng(stadiumLat, stadiumLng);

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
