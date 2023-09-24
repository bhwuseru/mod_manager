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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->string('category_name', 100);
            $table->unsignedBigInteger('mod_id')->unique();
            $table->unsignedBigInteger('nexusmod_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('category_type')->comment('1: nexus 2: custom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
