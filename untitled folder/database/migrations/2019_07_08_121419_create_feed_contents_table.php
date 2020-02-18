<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('permanent_link');
            $table->longText('title')->nullable();
            $table->longText('content')->nullable();
            $table->LongText('description')->nullable();
            $table->string('author')->nullable();
            $table->boolean('sent')->default(0);
            $table->unsignedInteger('rss_feed_id');
            $table->unsignedInteger('campaign_id')->nullable();
            $table->foreign('rss_feed_id')->references('id')->on('rss_feeds')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
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
        Schema::dropIfExists('feed_contents');
    }
}
