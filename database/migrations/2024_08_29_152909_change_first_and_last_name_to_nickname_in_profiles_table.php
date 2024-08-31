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
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');

            // `nickname` カラムを追加
            $table->string('nickname')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // `nickname` カラムを削除
            $table->dropColumn('nickname');

            // `first_name` と `last_name` カラムを再追加
            $table->string('first_name')->after('user_id');
            $table->string('last_name')->after('first_name');
        });
    }
};
