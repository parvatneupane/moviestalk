<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('feedback_replies', function (Blueprint $table) {
        $table->boolean('is_admin')->default(0)->after('user_id');
    });
}

public function down()
{
    Schema::table('feedback_replies', function (Blueprint $table) {
        $table->dropColumn('is_admin');
    });
}

};
