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
        Schema::table('stock_images', function (Blueprint $table) {
            $table->bigInteger('request_type')->default(2)->commit('1 = Camera, 2 = Uploade File')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_images', function (Blueprint $table) {
            //
        });
    }
};