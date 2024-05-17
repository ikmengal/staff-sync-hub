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
        Schema::create('valide_i_p_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('creator_id');
            $table->string('ip_address');
            $table->integer("status")->default(1)->comment('1=Active , 2=De-Active');
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valide_i_p_addresses');
    }
};
