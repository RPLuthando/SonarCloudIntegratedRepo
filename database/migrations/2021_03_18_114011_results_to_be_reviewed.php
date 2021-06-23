<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResultsToBeReviewed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_be_reviewed', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_number')->nullable();
            $table->integer('entity_id')->nullable();
            $table->integer('survey_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('survey_name')->nullable();
            $table->string('sjs_name')->nullable();
            $table->longText('sjs_title')->nullable();
            $table->longText('sjs_value')->nullable(); //If partial_data['score] !== custom_json['score] $score = artial_data['score]
            $table->longText('sjs_framework')->nullable();
            $table->decimal('sjs_score', 5, 2)->default(0.00); //If partial_data['score] !== custom_json['score] $score = artial_data['score]
            $table->string('sjs_comment')->nullable(); //If partial_data['score] !== custom_json['score] $score = artial_data['score]
            $table->string('sjs_standard')->nullable();
            $table->date('sjs_updated_at')->nullable();
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
        Schema::table('to_be_reviewed', function (Blueprint $table) {
            Schema::dropIfExists('to_be_reviewed');
        });
    }
}
