<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFromReplyEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('from_reply_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100);
            $table->boolean('confirmed')->default(0);
            $table->integer('sub_account_id')->unsigned()->nullable()->index();
            $table->string('token', 16)->unique()->nullable();
            $table->timestamps();

            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('from_reply_emails');
    }
}
