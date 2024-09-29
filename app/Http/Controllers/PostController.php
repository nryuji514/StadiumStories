<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Like;
use App\Models\User;
use App\Models\Profile;
use App\Models\Store;


class PostController extends Controller
{
    public function show(Store $store, Post $post )
    {
        $post->load('images', 'comments.user'); // 画像とコメントのリレーションをロード
        $data = $store;
        return view('stores.posts.show', compact('store', 'post', 'data'));
    }
    
   public function create(Store $store)
    {
        $data=$store;
        return view('stores.posts.create')->with(['store'=>$store,'data'=>$data]);
    }
    
    public function store(Request $request,Store $store)
    {
        // 新しい投稿を作成
        $post = new Post();
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->store_id = $store->id;
        $post->user_id = auth()->id();
    
        $post->save();
        // 投稿と同時に画像を保存できるようにする
        if ($request->hasFile('post_image')) {
            foreach ($request->file('post_image') as $file) {
            $imagePath = $file->store('uploads', 'public');
            $post->images()->create(['image_path' => $imagePath]);
            }
        }
        return redirect()->route('stores.posts.index', ['store' => $store->id]);
    }   

    
    public function edit(Post $post)
    {
        $data = $store;
        return view('posts.edit')->with(['post' => $post,'store' => $data]);
    }
    
    public function update(PostRequest $request, Post $post)
    {
         $input_post = $request->input('post');
    
        // 画像がアップロードされた場合の処理
        if ($request->hasFile('post_image')) {
            // 古い画像がある場合は削除
            if ($post->images()->exists()) {
                Storage::delete('public/' . $post->images->first()->image_path);
             $post->images()->delete();
            }
        
            // 新しい画像の保存
            $image = $request->file('post_image');
            $path = $image->store('uploads', 'public');
        
            // 画像のパスを保存
            $post->images()->create(['image_path' => $path]);
        }
    
        $post->fill($input_post)->save();
        return redirect('/posts/' . $post->id);
    }
    
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
    
    public function like(Post $post)
    {
        // ユーザーがすでにLikeしていない場合にLikeを追加
        if (!$post->likes()->where('user_id', Auth::id())->exists()) {
            $post->likes()->create(['user_id' => Auth::id()]);
        }

        // 最新のLike数を返す
        return response()->json([
            'success' => true,
            'likes_count' => $post->likes()->count()
        ]);
    }

    public function unlike(Post $post)
    {
        // ユーザーがLikeしている場合にLikeを削除
        $post->likes()->where('user_id', Auth::id())->delete();

        // 最新のLike数を返す
        return response()->json([
            'success' => true,
            'likes_count' => $post->likes()->count()
        ]);
    }
    public function destroy(Store $store,Post $post)
    {
        // 投稿を削除
        $post->delete();
        // 削除後のリダイレクト処理
        return redirect()->route('stores.posts.index', ['store' => $store->id])->with('success', '投稿が削除されました');
    }

}
