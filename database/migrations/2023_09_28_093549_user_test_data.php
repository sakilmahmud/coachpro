<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTestData extends Migration
{
    public function up()
    {
        Schema::table('user_test_data', function (Blueprint $table) {
            $table->unsignedBigInteger('attempt_id')->nullable();
            $table->foreign('attempt_id')->references('id')->on('attempts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_test_data', function (Blueprint $table) {
            $table->dropForeign(['attempt_id']);
            $table->dropColumn('attempt_id');
        });
    }
}
