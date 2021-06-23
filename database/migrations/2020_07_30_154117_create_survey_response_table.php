<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_response', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_id');
            $table->string('question_id');
            $table->integer('user_id');
            $table->integer('owner_id'); 
            $table->integer('survey_id');
            $table->boolean('saved')->default(0)->comment('this is a switch for an intermediate process! while saving a survey');
            $table->text('other')->nullable();
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
        Schema::dropIfExists('survey_response');
    }
}
