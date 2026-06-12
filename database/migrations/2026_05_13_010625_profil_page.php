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
        Schema::create('profile_page', function (Blueprint $table) {
            $table->id();
            $table->string('url_images')->nullable();
            $table->string('alt')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->json('content')->nullable();
            $table->enum('type', ['visi_misi', 'cover', 'profile'])->default('cover');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_page');
    }
};
