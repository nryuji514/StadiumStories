<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Stadium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class RouteController extends Controller
{
    /**
     * ルートの一覧を表示
     */
    public function index()
    {
        $stadiums = Stadium::with('routes')->get();
        $routes = Route::where('user_id', auth()->id())->with('stadium')->get();
        return view('routes.index', compact('routes','stadiums'));
    }

    /**
     * ルートの詳細を表示
     */
    public function show($id)
    {
        $route = Route::findOrFail($id);
        $route = Route::with('stadium')->findOrFail($id);
        return view('routes.show', compact('route'));
    }

    /**
     * ルートの作成フォームを表示
     */
    public function create()
    {
        $stadiums = Stadium::all();
        return view('routes.create', compact('stadiums'));
    }

    /**
     * ルートを保存
     */
    public function store(Request $request)
    {
        // バリデーション: 駅名と球場は必須
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'station_name' => 'required|string|max:255',
        ]);

        $stadiumId = $request->input('stadium_id');
        $stationName = $request->input('station_name');

        // 球場を取得
        $stadium = Stadium::findOrFail($stadiumId);
        
        // Google Maps Geocoding APIを使って駅名から緯度と経度を取得
        $coordinates = $this->getCoordinates($stationName);

        if ($coordinates) {
            $route = new Route();
            $route->user_id = auth()->id(); 
            $route->stadium_id = $stadiumId;
            $route->station_name = $stationName;
            $route->latitude = $coordinates['lat'];
            $route->longitude = $coordinates['lng'];
            $route->save();
            return redirect()->route('routes.index')->with('success', '経路が作成されました');
        }
        return redirect()->back()->withErrors('緯度と経度を取得できませんでした');
    }

    /**
     * ルートの編集フォームを表示
     */
    public function edit($id)
    {
        $route = Route::findOrFail($id);
        
        return view('routes.edit', compact('route'));
    }

    /**
     * ルートを更新
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'station_name' => 'required|string|max:255',
        ]);

        $route = Route::findOrFail($id);
        $stadiumId = $request->input('stadium_id');
        $stationName = $request->input('station_name');

        // Google Maps Geocoding APIを使って駅名から緯度と経度を取得
        $coordinates = $this->getCoordinates($stationName);

        if ($coordinates) {
            $route->stadium_id = $stadiumId;
            $route->station_name = $stationName;
            $route->latitude = $coordinates['lat'];
            $route->longitude = $coordinates['lng'];
            $route->save();
            return redirect()->route('routes.index')->with('success', '経路が更新されました');
        }
        return redirect()->back()->withErrors('緯度と経度を取得できませんでした');
    }

    /**
     * ルートを削除
     */
    public function destroy($id)
    {
        // 削除するルートを取得
        $route = Route::findOrFail($id);
        if (auth()->id() !== $route->user_id) {
            abort(403, '許可されていない操作です。');
        }

        $route->delete();
        return redirect()->route('routes.index')->with('success', '経路を削除しました。');
    }

    /**
     * 指定した名前の地点から緯度と経度を取得
     */
    private function getCoordinates($address)
    {
        $apiKey = env('GOOGLE_MAPS_API'); // 環境変数からAPIキーを取得
        $client = new Client();
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
        try {
            $response = $client->get($url, [
                'query' => [
                    'address' => $address,
                    'key' => $apiKey,
                ]
            ]);
            $body = json_decode($response->getBody(), true);
            if ($body['status'] === 'OK') {
                $location = $body['results'][0]['geometry']['location'];
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng']
                ];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
