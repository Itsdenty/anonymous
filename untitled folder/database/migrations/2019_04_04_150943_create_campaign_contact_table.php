<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_contact', function (Blueprint $table) {
            $table->unsignedInteger('campaign_id');
            $table->unsignedInteger('contact_id');
            $table->string('campaign_subject')->nullable();
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
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
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
        Schema::dropIfExists('campaign_contact');
    }
}
