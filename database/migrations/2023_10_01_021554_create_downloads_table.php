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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mod_id');
            $table->string('installationFile');
            $table->string('name');
            $table->string('modName');
            $table->string('gameName');
            $table->integer('modID');
            $table->integer('fileID');
            $table->string('url');
            $table->longText('description');
            $table->string('version');
            $table->string('newestVersion');
            $table->string('fileTime');
            $table->string('fileCategory');
            $table->integer('category');
            $table->string('repository');
            $table->string('installed');
            $table->string('uninstalled');
            $table->string('paused');
            $table->string('removed');
            $table->string('metaFilePath');
            $table->string('downloadPath');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
