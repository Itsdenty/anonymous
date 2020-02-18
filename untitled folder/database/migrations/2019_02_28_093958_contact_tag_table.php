<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_account_id')->unsigned()->nullable()->index();
            $table->integer('contact_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('tag_id')->references('id')->on('tags');


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
        Schema::dropIfExists('contact_tag');
    }
}
