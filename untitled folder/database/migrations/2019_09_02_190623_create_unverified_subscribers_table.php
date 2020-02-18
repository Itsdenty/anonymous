<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnverifiedSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unverified_subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('token', 16)->unique()->nullable();
            $table->uuid('form_id')->index();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
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
        Schema::dropIfExists('unverified_subscribers');
    }
}
