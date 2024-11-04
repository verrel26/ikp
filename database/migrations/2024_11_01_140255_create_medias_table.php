<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            // menyimpan file (gambar,video,berita,link/web)
            $table->string('file');
            // menyimpan data user yang mengupload file
            $table->unsignedBigInteger('user_id');
            // menyimpan type foto(png,jpg) video mp4 dll
            $table->string('type');
            // menyimpan path file
            $table->string('file_path');
            // menyimpan status izin dari peng upload dan downloader
            $table->string('status_izin');
            $table->timestamps();

            // Relasi ke tabel user untuk menyimpan siapa yang upload/post/download
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
