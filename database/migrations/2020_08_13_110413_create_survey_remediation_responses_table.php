<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRemediationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_remediation_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_id')->nullable();
            $table->string('question_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('owner_id')->nullable(); 
            $table->integer('survey_id')->nullable();
            $table->boolean('saved')->default(0)->comment('this is a switch for an intermediate process! while saving a survey');
            $table->string('period_plan')->nullable();
            $table->string('purchase')->nullable();
            $table->string('install')->nullable();
            $table->string('running')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();  
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
        Schema::dropIfExists('survey_remediation_responses');
    }
}
