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
        Schema::table('routes', function (Blueprint $table) {
            // distanceカラムを追加
            $table->decimal('distance', 8, 2)->nullable()->after('route_data');
            
            // durationカラムを追加
            $table->time('duration')->nullable()->after('distance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // distanceカラムとdurationカラムを削除
            $table->dropColumn(['distance', 'duration']);
        });
    }
};
