<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_segment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sub_account_id')->unsigned()->nullable()->index();
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('segment_id');
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
        Schema::dropIfExists('contact_segment');
    }
}
