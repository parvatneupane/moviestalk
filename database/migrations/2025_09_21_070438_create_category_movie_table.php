<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_movie');
    }
};
