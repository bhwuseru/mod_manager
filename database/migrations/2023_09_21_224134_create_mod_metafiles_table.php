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
        Schema::create('mod_metafiles', function (Blueprint $table) {
            $table->id();
            $table->string('directory_name', 255)->unique();
            $table->string('game_name')->nullable();
            $table->string('modid')->nullable()->comment('nexus modid');
            $table->string('version')->nullable();
            $table->string('newest_version')->nullable();
            $table->string('nexus_file_status')->nullable();
            $table->string('installation_file')->nullable()->comment('modorganizer download file name');
            $table->string('repository')->nullable()->comment('download website name');
            $table->integer('nexus_file_status');
            $table->string('ignoredVersion')->nullable();
            $table->unsignedBigInteger('fileid')->nullable()->comment('nexsus fileid');
            $table->longText('comments')->nullable();
            $table->longText('nexus_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_metafiles');
    }
};
