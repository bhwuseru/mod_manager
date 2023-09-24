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
        Schema::create('category_mod_metafile', function (Blueprint $table) {
            $table->id();
            $table->string('directory_name');
            $table->unsignedBigInteger('mod_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('mod_metafile_id');
            $table->integer('sortno'); // ソート順を保持するカラム

            // 外部キー制約
            // $table->foreign('mod_id')->references('id')->on('mods');
            // $table->foreign('category_id')->references('id')->on('categories');
            // $table->foreign('mod_metafile_id')->references('id')->on('mod_metafiles');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_mod_metafile');
    }
};
