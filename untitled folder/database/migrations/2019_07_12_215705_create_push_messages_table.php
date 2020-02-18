<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('form_id');
            $table->string('company');
            $table->string('title');
            $table->text('body');
            $table->string('website');
            $table->string('photo');
            $table->unsignedInteger('clicks')->nullable();
            $table->enum('delivered', ['YES', 'NO'])->default('NO');
            $table->timestamp('send_date');
            $table->unsignedInteger('push_count_recent');
            $table->string('date_string')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_messages');
    }
}
