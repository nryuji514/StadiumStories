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
        Schema::dropIfExists('routes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('routes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stadium_id')->constrained()->onDelete('cascade'); // 外部キー: stadiums テーブルの ID
        $table->string('station_name'); // 駅名
        $table->decimal('latitude', 10, 7); // 緯度
        $table->decimal('longitude', 10, 7); // 経度
        $table->timestamps();
    });
    }
};
