<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('reply')->nullable()->after('message'); // store admin reply
            $table->enum('status', ['pending', 'replied'])->default('pending')->after('reply'); // status column
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['reply', 'status']);
        });
    }
};
