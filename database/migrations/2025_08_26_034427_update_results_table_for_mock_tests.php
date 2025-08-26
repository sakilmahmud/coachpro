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
        Schema::table('results', function (Blueprint $table) {
            // Remove existing columns
            $table->dropColumn(['title', 'subject', 'attempt_id']);

            // Add new columns after user_id, making them nullable initially
            $table->unsignedBigInteger('course_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('mock_test_id')->nullable()->after('course_id');

            // Add foreign key constraints
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('mock_test_id')->references('id')->on('mock_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Remove foreign key constraints first
            $table->dropForeign(['course_id']);
            $table->dropForeign(['mock_test_id']);
            
            // Remove new columns
            $table->dropColumn(['course_id', 'mock_test_id']);

            // Re-add removed columns
            $table->string('title')->nullable();
            $table->string('subject')->nullable();
            $table->unsignedBigInteger('attempt_id')->nullable();
        });
    }
};
