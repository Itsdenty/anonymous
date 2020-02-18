<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSmsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_sms_campaign', function (Blueprint $table) {
            $table->unsignedInteger('sms_campaign_id');
            $table->unsignedInteger('contact_id');
            $table->boolean('sent')->default(0);
            $table->string('message_id')->nullable();
            $table->foreign('sms_campaign_id')->references('id')->on('sms_campaigns')->onDelete('cascade');
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
        Schema::dropIfExists('contact_sms_campaign');
    }
}
