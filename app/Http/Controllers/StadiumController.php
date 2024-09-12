<?php

namespace App\Http\Controllers;

use App\Models\Stadium;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


class StadiumController extends Controller
{
    /**
     * 球場の一覧を表示
     */
    public function index()
    {
        $stadiums = Stadium::all();
        return view('stadiums.index', compact('stadiums'));
    }

    /**
     * 球場の詳細を表示
     */
    public function show($id)
    {
        // 選択された球場に関連するルートを取得
        $routes = Route::where('stadium_id', $id)->get();
        $stadium = Stadium::findOrFail($id);
        // 経路データを取得
        $routes = $stadium->routes; 
        return view('stadiums.show', compact('stadium','routes'));
    }

    /**
     * 球場の作成フォームを表示
     */
    public function create()
    {
        return view('stadiums.create');
    }

    /**
     * 球場を保存
     */
    public function store(Request $request)
    {
        
        // バリデーション: 球場名は必須、その他のフィールドは不要
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $stadiumName = $request['name'];

        
        // Google Maps Geocoding APIを使って球場名から緯度と経度を取得
        $coordinates = $this->getCoordinates($stadiumName);

        // 取得したデータが有効か確認
        if (isset($coordinates)) {
            $stadium = new Stadium();
            $stadium->name = $stadiumName;
            $stadium->latitude = $coordinates['lat'];
            $stadium->longitude = $coordinates['lng'];
            $stadium->save();
            return redirect()->route('stadiums.index')->with('success', '球場が作成されました');
        }
        return redirect()->back()->withErrors('緯度と経度を取得できませんでした');
    }

    /**
     * 球場の編集フォームを表示
     */
    public function edit($id)
    {
        $stadium = Stadium::findOrFail($id);
        return view('stadiums.edit', compact('stadium'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $stadium = Stadium::findOrFail($id);

        // リクエストから球場名を取得
        $stadiumName = $request->input('name');

        // Google Maps Geocoding APIを使って球場名から緯度と経度を取得
        $coordinates = $this->getCoordinates($stadiumName);

        if ($coordinates) {
            $stadium->latitude = $coordinates['lat'];
            $stadium->longitude = $coordinates['lng'];
        }

        // 球場名の更新
        $stadium->name = $stadiumName;
        $stadium->save();

        return redirect()->route('stadiums.index')->with('success', '球場が更新されました');
    }

    /**
     * 球場を削除
     */
    public function destroy($id)
    {
        $stadium = Stadium::findOrFail($id);
        $stadium->delete();

        return redirect()->route('stadiums.index')->with('success', '球場が削除されました');
    }
    private function getCoordinates($stadiumName) 
    { 
        $apiKey = env('GOOGLE_MAPS_API'); // 環境変数からAPIキーを取得
        $client = new Client();
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
        try {
            $response = $client->get($url, [
                'query' => [
                    'address' => $stadiumName,
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
