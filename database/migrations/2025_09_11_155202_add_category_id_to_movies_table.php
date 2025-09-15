<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('movies', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id')->nullable();  // Add foreign key column
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');  // Add foreign key constraint
    });
}

public function down(): void
{
    Schema::table('movies', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}

};
