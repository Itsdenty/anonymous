<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushNotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifs', function (Blueprint $table) {
            $table->increments('id');
            // $table->unsignedInteger('user_id');
            $table->unsignedInteger('company_id');
            $table->string('title');
            $table->string('photo')->nullable();
            $table->string('body');
            $table->string('website');
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
        Schema::dropIfExists('push_notifs');
    }
}
