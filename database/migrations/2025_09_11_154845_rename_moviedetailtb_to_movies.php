<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->string('title');                       // movie title
        $table->text('description')->nullable();       // movie description
        $table->date('release_date')->nullable();      // release date
        $table->string('runtime')->nullable();         // runtime e.g. "120 min"
        $table->string('director')->nullable();        // director name
        $table->string('content_rating')->nullable();  // e.g. PG-13, R
        $table->string('writer')->nullable();          // writer(s)
        $table->string('production')->nullable();      // production company
        $table->string('genres')->nullable();          // e.g. Action, Drama
        $table->text('cast')->nullable();              // main cast
        $table->string('poster')->nullable();          // poster image path
        $table->string('trailer_url')->nullable();     // YouTube/trailer link

        $table->timestamps();
    });
}

    
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            
        });
    }
};
