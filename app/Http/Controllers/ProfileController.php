<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $user = Auth::user();
        if (!$profile) {
            return redirect()->route('profiles.create')->with('message', 'プロフィールが見つかりません。作成してください。');
        }
        return view('profiles.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $user = Auth::user();
        // プロフィールが存在しない場合の処理
        if (!$profile) {
            return redirect()->route('profiles.create')->with('message', 'プロフィールが見つかりません。作成してください。');
        }
        return view('profiles.edit', compact('profile'));
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture_url' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        // プロフィール画像の保存処理
        if ($request->hasFile('profile_picture_url')) {
            $path = $request->file('profile_picture_url')->store('profile_pictures', 'public');
            $validated['profile_picture_url'] = $path;
        }

        // プロフィールの作成
        $profile = auth()->user()->profile()->create($validated);

        // プロフィールの作成が完了した後に、`show` 画面にリダイレクト
        return redirect()->route('profiles.show', ['profile' => $profile->id])
                         ->with('success', 'プロフィールが作成されました！');
    }

    public function update(Request $request, Profile $profile)
    {
        $user = auth()->user();
        // プロフィールが見つからない場合のエラーハンドリング
        if ($user->profile->id !== $profile->id) {
            return redirect()->back()->withErrors('プロフィールが見つかりません。');
        }

        // バリデーション
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture_url' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        // プロフィール画像の保存処理
        if ($request->hasFile('profile_picture_url')) {
            $path = $request->file('profile_picture_url')->store('profile_pictures', 'public');
            $validated['profile_picture_url'] = $path;
        }

        // プロフィールの更新
        $profile->update($validated);

        // 更新後に `show` 画面にリダイレクト
        return redirect()->route('profiles.show', ['profile' => $profile->id])
                         ->with('success', 'プロフィールが更新されました！');
    }
}
