<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('options_selected');
            $table->integer('count')->default(1);
            $table->uuid('form_id')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_options');
    }
}
