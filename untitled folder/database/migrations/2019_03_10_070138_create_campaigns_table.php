<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->longText('subject_a')->nullable();
            $table->longText('subject_b')->nullable();
            $table->datetime('send_date')->nullable();
            $table->datetime('actual_send_date')->nullable();
            $table->integer('from_reply_id')->unsigned()->nullable();
            $table->integer('smtp_id')->unsigned()->nullable();
            $table->integer('mail_api_id')->unsigned()->nullable();
            $table->longText('content')->nullable();
            $table->longText('editable_content')->nullable();
            $table->boolean('ab_test')->default(0);
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
        Schema::dropIfExists('campaigns');
    }
}
