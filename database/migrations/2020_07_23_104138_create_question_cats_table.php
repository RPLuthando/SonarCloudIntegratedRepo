<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_cats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_cat_name');
            $table->boolean('question_cat_status');
            $table->string('survey_type')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('target_date')->nullable();
            $table->string('survey_owner_id')->nullable();
            $table->text('summary')->nullable();
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
        Schema::dropIfExists('question_cats');
    }
}
