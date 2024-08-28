<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Category;


class PostController extends Controller
{
    public function index(Post $post)
    {
        //index.bladeに取得したデータを渡す
        return view('posts.index')->with([
            'posts'=> $post->getPaginateByLimit()
            ]);
        //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
    }

    public function show(Post $post)
    {
        $post->load('images', 'comments.user'); // 画像とコメントのリレーションをロード
        return view('posts.show', ['post' => $post]);
        
    }
    
   public function create(Category $category)
    {
        return view('posts.create')->with(['categories' => $category->get()]);
    }
    
    public function store(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $post->fill($input)->save();
    
        // 投稿と同時に画像を保存できるようにする
        if ($request->hasFile('post_image')) {
            foreach ($request->file('post_image') as $file) {
            $imagePath = $file->store('uploads', 'public');
            $post->images()->create(['image_path' => $imagePath]);
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
    
    
}
