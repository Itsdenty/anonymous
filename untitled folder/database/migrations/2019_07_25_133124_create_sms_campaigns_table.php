<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->datetime('send_date')->nullable();
            $table->datetime('actual_send_date')->nullable();
            $table->integer('sms_mms_integration_id')->unsigned()->nullable();
            $table->longText('content')->nullable();
            $table->string('image_url')->nullable();
            $table->longText('filter_query')->nullable();
            $table->enum('status', ['draft', 'progress', 'sent', 'later'])->default('draft');
            $table->unsignedInteger('sub_account_id')->nullable();
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('sms_campaigns');
    }
}
