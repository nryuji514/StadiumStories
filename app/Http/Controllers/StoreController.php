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

        // ビューに店舗情報と投稿情報を渡す
        return view('stores.posts.index', compact('store', 'posts'));
    }

    public function searchAndSave(Route $route)
    {
        $latitude = $route->latitude;
        $longitude = $route->longitude;
        $language = 'ja'; // 日本語

        // APIリクエストの送信
        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'location' => "$latitude,$longitude",
            'radius' => 500, // 500メートル以内
            'type' => 'restaurant|convenience_store', // 検索タイプを指定
            'language' => $language,
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

             // 店舗の種類を保存するために初期化
            $storeType = null;
            $types = $storeData['types'];

            // 店舗の種類を設定
            if (in_array('convenience_store', $types)) {
                $storeType = 'convenience_store';
            } elseif (in_array('restaurant', $types)) {
                $storeType = 'restaurant';
            }

            // 店舗の種類が設定されていれば保存
            if ($storeType) {
                Store::updateOrCreate(
                    ['name' => $storeData['name'], 'route_id' => $route->id], // 重複を防ぐための条件
                    [
                        'address' => $storeData['vicinity'],
                        'latitude' => $storeData['geometry']['location']['lat'],
                        'longitude' => $storeData['geometry']['location']['lng'],
                        'photo_url' => $photoUrl,
                        'type' => $storeType, // 店舗の種類を保存
                    ]
                );
            }
        }
        return redirect()->route('routes.show', $route->id)->with('success', '店舗情報が更新されました');
    }
}
