<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSmsCampaignLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_sms_campaign_link', function (Blueprint $table) {
            $table->unsignedInteger('sms_campaign_link_id');
            $table->unsignedInteger('contact_id');
            $table->integer('click_count')->default(0);
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('domain')->nullable();
            $table->string('country_name')->nullable();
            $table->foreign('sms_campaign_link_id')->references('id')->on('sms_campaign_links')->onDelete('cascade');
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
        Schema::dropIfExists('contact_sms_campaign_link');
    }
}
