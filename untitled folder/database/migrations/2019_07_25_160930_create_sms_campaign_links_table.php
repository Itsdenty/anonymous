<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsCampaignLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_campaign_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sms_campaign_id')->unsigned();
            $table->longText('actual_url');
            $table->string('replacement_url');
            $table->foreign('sms_campaign_id')->references('id')->on('sms_campaigns')->onDelete('cascade');
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
        Schema::dropIfExists('sms_campaign_links');
    }
}
