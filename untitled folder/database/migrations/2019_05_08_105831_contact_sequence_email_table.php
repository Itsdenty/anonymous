<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactSequenceEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('contact_sequence_email', function (Blueprint $table) {
            $table->unsignedInteger('sequence_email_id');
            $table->unsignedInteger('contact_id');
            $table->datetime('send_time')->nullable();
            $table->boolean('started')->default(0);
            $table->boolean('sent')->default(0);
            $table->boolean('opened')->default(0);
            $table->boolean('unsubscribed')->default(0);
            $table->integer('open_count')->default(0);
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('domain')->nullable();
            $table->string('country_name')->nullable();
            $table->string('message_id')->nullable();
            $table->boolean('complained')->default(0);
            $table->boolean('bounced')->default(0);
            $table->boolean('hard_bounced')->default(0);
            $table->foreign('sequence_email_id')->references('id')->on('sequence_emails')->onDelete('cascade');
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
        //
        Schema::dropIfExists('contact_sequence_email');
    }
}
