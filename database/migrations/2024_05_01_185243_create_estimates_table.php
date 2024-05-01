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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->integer("creator_id")->nullable();
            $table->integer("company_id")->nullable();
            $table->integer("request_id")->nullable();
            $table->string("title")->nullable();
            $table->longText("description")->nullable();
            $table->string("price")->nullable();
            $table->longText("raw_data")->nullable();
            $table->integer("status")->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
