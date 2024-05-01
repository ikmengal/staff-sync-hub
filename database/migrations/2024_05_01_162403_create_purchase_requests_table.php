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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string("creator")->nullable()->comment("can be integer or direct User email");
            $table->integer("company_id")->nullable();
            $table->string("subject")->nullable();
            $table->longText("description")->nullable();
            $table->string("modified_by")->nullable();
            $table->datetime("modified_at")->nullable();
            $table->longText("raw_data")->nullable();
            $table->integer("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
