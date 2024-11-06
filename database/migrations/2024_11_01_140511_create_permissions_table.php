<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            // menyimpan data dari table media
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('owner_id');
            // menyimpan status untuk file yang di upload/didownload (pending,approved,reject)
            $table->boolean('is_approved')->default(false);
            $table->foreign('media_id')->references('id')->on('medias')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
