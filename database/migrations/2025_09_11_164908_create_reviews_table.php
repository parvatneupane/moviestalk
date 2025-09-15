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
         Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id'); // Foreign key to movies table
            $table->unsignedBigInteger('user_id'); // Foreign key to users table (if needed)
            $table->text('review');
            $table->integer('rating')->nullable(); // Rating for the review, e.g. 1-5
            $table->timestamps();

            // Add foreign key constraints (if needed)
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Optional: if reviews are tied to users
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
