<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // 外部キー制約の削除
            $table->dropForeign(['user_id']); // user_id は実際の外部キーのカラム名に変更
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // 外部キー制約の追加
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 実際の制約に合わせて変更
        });
    }
};
