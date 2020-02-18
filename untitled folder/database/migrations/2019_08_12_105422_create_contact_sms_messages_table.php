<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSmsMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_sms_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('content');
            $table->boolean('received')->default(0);
            $table->boolean('unread')->default(0);
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('sms_mms_integration_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('sms_mms_integration_id')->references('id')->on('sms_mms_integrations')->onDelete('cascade');
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
        Schema::dropIfExists('contact_sms_messages');
    }
}
