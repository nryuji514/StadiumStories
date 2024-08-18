<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // カテゴリーの一覧を表示
    public function index(Category $category)
    {
        // カテゴリーの一覧を取得
        $categories = $category->all();

        // ビューにデータを渡す
        return view('categories.index', compact('categories'));
    }

    // 他のアクションも追加可能
}
