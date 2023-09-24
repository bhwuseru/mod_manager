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
            $table->longText('installation_file')->nullable()->comment('modorganizer download file name');
            $table->string('url')->nullable();
            $table->boolean('has_custom_url')->default(false);
            $table->string('converted')->default('false');
            $table->string('validated')->default('false');
            $table->integer('size')->nullable();
            $table->string('ignored_version')->nullable();
            $table->string('repository')->nullable()->comment('download website name');
            $table->string('ignoredVersion')->nullable();
            $table->unsignedBigInteger('fileid')->nullable()->comment('nexsus fileid');
            $table->longText('comments')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('nexus_description')->nullable();
            $table->dateTime('last_nexus_query')->nullable();
            $table->dateTime('last_nexus_update')->nullable();
            $table->dateTime('nexus_last_modified')->nullable();
            $table->unsignedBigInteger('tracked')->default(0);
            $table->unsignedBigInteger('endorsed')->default(0);
            $table->string('color')->nullable();
            $table->string('archive')->nullable();
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
