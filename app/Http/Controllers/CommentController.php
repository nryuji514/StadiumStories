<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
    $request->validate([
        'comment' => 'required|max:1000',
    ]);
    
    $comment = new Comment();
    $comment->comment = $request->input('comment');
    $comment->post_id = $post->id;
    $comment->user_id = auth()->user()->id; // 現在のログインユーザーのIDを設定
    $comment->save();

    return redirect()->route('show', $post->id);
    }
    public function destroy(Comment $comment)
    {
        // コメントの削除
        $this->authorize('delete', $comment);

        $comment->delete();

        // リダイレクトまたはJSONレスポンス
        return redirect()->back()->with('success', 'Comment deleted successfully.');
}
}
