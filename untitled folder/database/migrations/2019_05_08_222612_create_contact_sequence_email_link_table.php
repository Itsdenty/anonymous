<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSequenceEmailLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_sequence_email_link', function (Blueprint $table) {
            $table->unsignedInteger('sequence_email_link_id');
            $table->unsignedInteger('contact_id');
            $table->integer('click_count')->default(0);
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('domain')->nullable();
            $table->string('country_name')->nullable();
            $table->foreign('sequence_email_link_id')->references('id')->on('sequence_email_links')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
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
        Schema::dropIfExists('contact_sequence_email_link');
    }
}
