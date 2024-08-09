<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * @return void
     */
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uni_num')->references('uni_num')->on('students')->onDelete('restrict');
            $table->foreignId('question_id')->references('id')->on('questions')->onDelete('restrict');
            $table->foreignId('choice_id')->references('id')->on('choices')->onDelete('restrict');
            $table->foreignId('quiz_id')->references('id')->on('quizes')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
}
