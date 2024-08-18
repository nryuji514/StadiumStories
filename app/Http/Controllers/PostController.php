<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostImage;


class PostController extends Controller
{
    public function index(Post $post)
    {
        //クライアントインスタンスの生成
        $client = new \GuzzleHttp\Client();
        
        //GET受信するURL
        $url = 'https://teratail.com/api/v1/questions';
        
        //リクエスト送信と返却データの取得
        //Bearerトークンにアクセストークンを指定して認証を行う
        $response = $client->request(
            'GET',
            $url,
            ['Bearer' => config('services.teratail.token')]
        );
        
        //API通信で取得したデータはjson形式なのでPHPファイルに対応した連想配列にでコードする
        $questions = json_decode($response->getBody(), true);
        
        //index.bladeに取得したデータを渡す
        return view('posts.index')->with([
            'posts'=> $post->getPaginateByLimit(),
            'questions' => $questions['questions'],
            ]);
        //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
    }

    public function show(Post $post)
    {
        return view('posts.show')->with(['post'=>$post]);
    }
    
   public function create(Category $category)
    {
        return view('posts.create')->with(['categories' => $category->get()]);
    }
    
    public function store(PostRequest $request, Post $post)
    {
        // 投稿内容の取得
        $input = $request->input('post');
        
        // ログインしているユーザーのIDを追加
        $input['user_id'] = auth()->id(); // 現在のユーザーIDを取得し、user_id フィールドに設定
        $input['category_id'] = $request->input('category_id'); // カテゴリーIDをセット
        
        // データをデバッグ
        dd($input);

        // 投稿の作成
        $post = Post::create($input); // createメソッドを使用して、$fillable プロパティに指定されたすべての属性を一度に設定します

        // 画像がアップロードされたか確認
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // 画像を保存し、パスを取得
                $path = $image->store('images', 'public');

                // 画像情報を保存
                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $path,
                ]);
            }
        }
        return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
        return view('posts.edit')->with(['post' => $post]);
    }
    
    public function update(PostRequest $request, Post $post)
    {
        $input_post = $request['post'];
        $post->fill($input_post)->save();
        
        // 画像がアップロードされたか確認
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // 画像を保存し、パスを取得
                $path = $image->store('images', 'public');

                // 画像情報を保存
                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $path,
                ]);
            }
        }
        
    
        return redirect('/posts/' . $post->id);
    }
    
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
    
    
}