<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_id');
            $table->text('question_options');
            $table->string('score_current')->nullable();
            $table->string('score_future')->nullable();
            $table->string('ideal_standard')->nullable();
            $table->string('acceptable_standard')->nullable();
            $table->string('future')->nullable();
            $table->string('current')->nullable();
            $table->string('purchase')->nullable();
            $table->string('install')->nullable();
            $table->string('running')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->string('option_type')->nullable();
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
        Schema::dropIfExists('question_options');
    }
}
