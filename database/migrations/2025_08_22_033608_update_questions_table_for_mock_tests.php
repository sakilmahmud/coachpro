<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionsTableForMockTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['subject', 'title']);
            $table->unsignedBigInteger('course_id')->nullable()->after('id');
            $table->unsignedBigInteger('mock_test_id')->nullable()->after('course_id');

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('mock_test_id')->references('id')->on('mock_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['mock_test_id']);
            $table->dropColumn(['mock_test_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id']);
            $table->string('subject')->nullable();
            $table->string('title')->nullable();
        });
    }
}
