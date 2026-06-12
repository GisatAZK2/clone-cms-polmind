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
        Schema::create('karakter', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_konten');
            $table->string('url_image')->nullable();
            $table->string('alt')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karakter');
    }
};
