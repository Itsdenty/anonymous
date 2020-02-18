<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('url')->nullable();
            $table->enum('settings', ['single', 'digest'])->default('single');
            $table->enum('digest_option', ['daily', 'weekly'])->nullable();
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->nullable();            
            $table->integer('from_reply_id')->unsigned()->nullable();
            $table->integer('smtp_id')->unsigned()->nullable();
            $table->integer('mail_api_id')->unsigned()->nullable();
            $table->longText('filter_query')->nullable();
            $table->dateTime('sent_at')->nullable();
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
        Schema::dropIfExists('rss_feeds');
    }
}
