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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 店舗名
            $table->string('address')->nullable(); // 住所
            $table->decimal('latitude', 10, 7)->nullable(); // 緯度
            $table->decimal('longitude', 10, 7)->nullable(); // 経度
            $table->string('place_id')->nullable(); // Google Place ID
            $table->foreignId('route_id')->constrained()->onDelete('cascade'); // routesテーブルとの関連
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
