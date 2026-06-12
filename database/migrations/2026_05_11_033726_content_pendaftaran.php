<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['atas', 'bawah'])->default('atas');
            $table->json('content');
            $table->text('kata_kata')->nullable();
            $table->date('tahun_buka')->nullable();         
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_pendaftaran');
    }
};