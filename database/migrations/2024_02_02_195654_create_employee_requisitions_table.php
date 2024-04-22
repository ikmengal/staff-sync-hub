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
        Schema::create('employee_requisitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('createdBy');
            $table->bigInteger('manager_id')->nullable();
            $table->bigInteger('shift_id')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('title')->nullable();
            $table->string('max_salary')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('min_education')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1)->comment('1-active, 0-deactive');
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_requisitions');
    }
};
