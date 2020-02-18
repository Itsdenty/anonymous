<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsMmsIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_mms_integrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_sid');
            $table->string('auth_token');
            $table->string('twilio_number');
            $table->boolean('current_settings')->default(0);
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
        Schema::dropIfExists('sms_mms_integrations');
    }
}
