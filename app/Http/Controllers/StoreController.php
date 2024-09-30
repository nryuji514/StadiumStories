<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Store;
use App\Models\Route;
use App\Models\Post;

class StoreController extends Controller
{
    public function index(Store $store)
    {
        // 特定の店舗に関連する投稿を取得
        $posts = $store->posts()->with('images', 'user', 'likes','comments.user')->paginate(10);
        $data = $store;
        $route = $store->route;  
        // ビューに店舗情報と投稿情報を渡す
        return view('stores.posts.index')->with(['posts'=>$posts, 'store'=>$store, 'data'=>$data,'route'=>$route]);
    }
    public function show($id)
    {
        // ルート情報を取得
        $route = Route::with(['stadium', 'stores'])->findOrFail($id);

        // 飲食店、コンビニ、スーパーをそれぞれフィルタリング
        $restaurants = $route->stores->where('type', 'restaurant');
        $convenienceStores = $route->stores->where('type', 'convenience_store');
        $supermarkets = $route->stores->where('type', 'supermarket');

        // ビューにデータを渡す
        return view('routes.show', compact('route', 'restaurants', 'convenienceStores', 'supermarkets'));
    }


    public function searchAndSave(Route $route)
    {
        $latitude = $route->latitude;
        $longitude = $route->longitude;
        $language = 'ja'; // 日本語
        
        // 3つの種類の場所をそれぞれ取得
        $this->fetchAndSaveStores($latitude, $longitude, 'restaurant', $route);
        $this->fetchAndSaveStores($latitude, $longitude, 'convenience_store', $route);
        $this->fetchAndSaveStores($latitude, $longitude, 'supermarket', $route);

        return redirect()->route('routes.show', $route->id)->with('success', '店舗情報が更新されました');
    }

    // 店舗データを取得して保存する共通メソッド
    private function fetchAndSaveStores($latitude, $longitude, $type, $route)
    {
        // APIリクエストの送信
        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'location' => "$latitude,$longitude",
            'radius' => 500, // 半径500メートル以内
            'type' => $type, // 場所の種類を指定
            'language' => 'ja', // 日本語
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ]);

        $results = $response->json()['results'];

        // 店舗データの保存
        foreach ($results as $storeData) {
            $photoUrl = null;

            // 写真情報が存在する場合、URLを取得
            if (isset($storeData['photos'])) {
                $photoReference = $storeData['photos'][0]['photo_reference'];
                $photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$photoReference}&key=" . env('GOOGLE_MAPS_API_KEY');
            }

            // 店舗の種類を保存する
            Store::updateOrCreate(
                [
                    'name' => $storeData['name'],
                    'route_id' => $route->id,
                    'type' => $type // レストラン、コンビニ、スーパーのいずれか
                ],
                [
                    'address' => $storeData['vicinity'],
                    'latitude' => $storeData['geometry']['location']['lat'],
                    'longitude' => $storeData['geometry']['location']['lng'],
                    'photo_url' => $photoUrl,
                ]
            );
        }
    }

}
