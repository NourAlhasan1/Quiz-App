<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uni_num')->references('uni_num')->on('students')->cascadeOnDelete;
            $table->foreignId('quiz_id')->references('id')->on('quizes')->cascadeOnDelete;
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
        Schema::dropIfExists('student_quiz');
    }
}
