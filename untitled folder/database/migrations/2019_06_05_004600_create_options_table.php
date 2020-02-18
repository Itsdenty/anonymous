<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('count')->default(0);
            $table->unsignedInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->unsignedInteger('next_question_id')->nullable();
            $table->foreign('next_question_id')->references('id')->on('questions')->onDelete('set null');
            $table->unsignedInteger('outcome_id')->nullable();
            $table->foreign('outcome_id')->references('id')->on('outcomes')->onDelete('set null');
            $table->Integer('option_value')->nullable();
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
        Schema::dropIfExists('options');
    }
}
