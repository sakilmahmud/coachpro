<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('question_attempts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('student_id'); // Assuming you have a student table
        $table->unsignedBigInteger('question_id');
        $table->unsignedBigInteger('selected_option_id')->nullable();
        $table->boolean('is_correct')->default(false);
        $table->timestamps();

        $table->foreign('student_id')->references('id')->on('students');
        $table->foreign('question_id')->references('id')->on('questions');
        // You can add foreign keys for selected_option_id if you want
    });
}

    /**
     * Reverse the migrations.php artisan migrate

     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_attempts');
    }
}
