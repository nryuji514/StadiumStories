<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>球場を追加</title>
    
    <style>
        /* ここにスタイルが続きます */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-family: 'Arial Black', sans-serif; 
            text-align: center;
            color: #218838;
            font-size: 50px;
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
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #218838;
        }
        #map {
            height: 400px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        nav {
            margin-bottom: 20px; /* ナビゲーションの下にマージンを追加 */
        }

        nav a {
            font-size: 18px; /* ナビゲーションリンクのフォントサイズ */
            color: #007bff; /* ナビゲーションリンクの色 */
            text-decoration: none; /* 下線を消す */
        }

        nav a:hover {
            text-decoration: underline; /* ホバー時に下線を追加 */
        }
    </style>
    <script>
        let map;
        let service;
        let marker; // マーカーを格納する変数

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 35.6895, lng: 139.6917 }, // 東京をデフォルトの中心に設定
                zoom: 8
            });
            service = new google.maps.places.PlacesService(map);
        }

        function searchPlace() {
            const placeName = document.getElementById('name').value;
            const request = {
                query: placeName,
                fields: ['name', 'geometry'],
            };

            service.findPlaceFromQuery(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results && results.length > 0) {
                    const location = results[0].geometry.location;

                    // 地図を球場の位置に移動し、ズームする
                    map.setCenter(location);
                    map.setZoom(15); // 球場にズーム

                    // 以前のマーカーを削除
                    if (marker) {
                        marker.setMap(null);
                    }

                    // 新しいマーカーを作成
                    marker = new google.maps.Marker({
                        map: map,
                        position: location
                    });

                    // 緯度と経度を隠しフィールドに設定
                    document.getElementById('latitude').value = location.lat();
                    document.getElementById('longitude').value = location.lng();
                } else {
                    alert('場所が見つかりませんでした。');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            document.getElementById('name').addEventListener('input', searchPlace);
        });
    </script>
</head>
<body>
<x-app-layout>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&libraries=places&callback=initMap" async defer></script>
    <div class="container">
        <nav>
            <a href="{{ route('routes.index') }}">＜ 戻る</a>
        </nav>
        <h1>新規球場追加</h1>

        <form action="{{ route('stadiums.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">球場名</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="category">カテゴリー</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="">カテゴリーを選択</option>
                    <option value="セリーグ">セリーグ</option>
                    <option value="パリーグ">パリーグ</option>
                    <option value="地方球場">地方球場</option>
                </select>
            </div>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit" class="btn">球場を追加する</button>
        </form>

        <!-- 球場の位置を表示する地図 -->
        <div id="map"></div>
    </div>
</x-app-layout>
</body>
</html>
