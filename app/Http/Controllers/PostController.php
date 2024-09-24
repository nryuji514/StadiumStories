<?php

namespace App\Http\Controllers;

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
        return view('stores.posts.show', ['store' => $store, 'post' => $post]);
    }
    
   public function create(Store $store)
    {
        return view('stores.posts.create', compact('store'));
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
        return view('posts.edit')->with(['post' => $post]);
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
        $like = Like::firstOrCreate(
            ['user_id' => auth()->id(), 'post_id' => $post->id]
        );

        return back();
    }

    public function unlike(Post $post)
    {
        $like = Like::where('user_id', auth()->id())
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            $like->delete();
        }
        return back();
    }
}
