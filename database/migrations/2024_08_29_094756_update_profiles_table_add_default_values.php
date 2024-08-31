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
        Schema::table('profiles', function (Blueprint $table) {
            // first_name と last_name のデフォルト値を設定
            $table->string('first_name')->default('')->change();
            $table->string('last_name')->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // デフォルト値を削除（必要に応じて）
            $table->string('first_name')->change();
            $table->string('last_name')->change();
        });
    }
};
