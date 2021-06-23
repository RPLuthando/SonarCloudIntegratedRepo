<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('survey_id')->unsigned()->nullable();
            $table->foreign('survey_id')->references('id')->on('surveys');
            $table->longText('json')->nullable();           
            $table->longText('custom_json')->nullable();
            $table->longText('review_json')->nullable();
            $table->longText('review_custom_json')->nullable();
            $table->longText('partial_data')->nullable();
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->decimal('inital_score', 5, 2)->default(0.00);
            $table->decimal('review_score', 5, 2)->default(0.00);
            $table->longText('manage_score')->nullable();
            $table->integer('change_count',0);
            $table->integer('res_uid')->unsigned()->nullable();
            $table->foreign('res_uid')->references('id')->on('users');
            $table->string('status')->nullable();
            $table->integer('entity_id')->unsigned();
            $table->foreign('entity_id')->references('id')->on('entities');
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
        Schema::dropIfExists('results');
    }
}
