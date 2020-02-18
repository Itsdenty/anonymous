<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSequenceEmailLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequence_email_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequence_email_id')->unsigned();
            $table->longText('actual_url');
            $table->string('replacement_url');
            $table->foreign('sequence_email_id')->references('id')->on('sequence_emails')->onDelete('cascade');
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
        Schema::dropIfExists('sequence_email_links');
    }
}
