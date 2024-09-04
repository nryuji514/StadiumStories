<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>マップページ</title>
    <!-- Google Maps JavaScript APIの読み込み -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDUyDjo9b7wtk_8EM4wk9gnXc_WaSWUQ&callback=initMap" async defer></script>
    <style>
        #map {
            height: 60vh;
            width: 90%;
            margin: 20px auto;
            border: 2px solid #ccc;
            border-radius: 8px;
        }
        .custom-map-control-button {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            cursor: pointer;
            margin: 10px;
            padding: 8px 12px;
            font-size: 14px;
            text-align: center;
            color: #333;
        }
        header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        #directions-panel {
            width: 90%;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .input-group {
            margin-bottom: 10px;
            text-align: center;
        }
        .input-group input {
            width: 40%;
            padding: 8px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>マップページ</h1>
    </header>

    <!-- 出発地点と終着地点の入力フォーム -->
    <div class="input-group">
        <input type="text" id="start" placeholder="出発地点を入力">
        <input type="text" id="end" placeholder="終着地点を入力">
        <button id="submit" class="custom-map-control-button">経路を検索</button>
    </div>

    <!-- マップを表示するための要素 -->
    <div id="map"></div>

    <!-- 経路情報を表示するためのパネル -->
    <div id="directions-panel"></div>

    <script>
        let map, infoWindow, directionsService, directionsRenderer;

        function initMap() {
            // デフォルト位置（東京）
            const defaultLocation = { lat: 35.6895, lng: 139.6917 };

            // マップを初期化
            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 15,
                gestureHandling: "greedy"
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
            directionsRenderer.setPanel(document.getElementById('directions-panel'));

            infoWindow = new google.maps.InfoWindow();

            // 現在地を取得し、マップの中心に設定
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        // 現在地をマップの中心に設定
                        map.setCenter(pos);

                        // 現在地の情報ウィンドウを表示
                        infoWindow.setPosition(pos);
                        infoWindow.setContent("現在地が見つかりました。");
                        infoWindow.open(map);

                        // 現在地にマーカーを追加
                        new google.maps.Marker({
                            position: pos,
                            map: map,
                            title: "現在地",
                            icon: {
                                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                            }
                        });
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // ブラウザがジオロケーションをサポートしていない場合
                handleLocationError(false, infoWindow, map.getCenter());
            }

            // 経路検索ボタンのクリックイベントを設定
            document.getElementById("submit").addEventListener("click", () => {
                calculateAndDisplayRoute(directionsService, directionsRenderer);
            });
        }

        function calculateAndDisplayRoute(directionsService, directionsRenderer) {
            const start = document.getElementById("start").value;
            const end = document.getElementById("end").value;

            if (start && end) {
                directionsService.route(
                    {
                        origin: start,
                        destination: end,
                        travelMode: google.maps.TravelMode.DRIVING,
                    },
                    (response, status) => {
                        if (status === "OK") {
                            directionsRenderer.setDirections(response);
                        } else {
                            window.alert("経路の取得に失敗しました: " + status);
                        }
                    }
                );
            } else {
                window.alert("出発地点と終着地点を入力してください。");
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation
                    ? "エラー: ジオロケーション サービスが失敗しました。"
                    : "エラー: お使いのブラウザはジオロケーションをサポートしていません。"
            );
            infoWindow.open(map);
        }

        window.initMap = initMap;
    </script>
</body>
</html>
