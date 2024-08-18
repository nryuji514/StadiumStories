<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'post.title' => 'required|string|max:255',
            'post.body' => 'required|string',
            'post.category_id' => 'required|exists:categories,id',
        ];
    }

    public function authorize()
    {
        return true; // 必要に応じて認可ロジックを追加
    }
}
