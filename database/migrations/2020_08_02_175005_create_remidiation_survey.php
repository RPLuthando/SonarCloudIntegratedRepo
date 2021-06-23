<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemidiationSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remidiation_survey', function (Blueprint $table) {
            $table->increments('id');
            $table->string('survey_category_name')->nullable();
            $table->boolean('category_status')->nullable();
            $table->string('survey_type')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('target_date')->nullable();
            $table->string('survey_owner_id')->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('remidiation_survey');
    }
}
