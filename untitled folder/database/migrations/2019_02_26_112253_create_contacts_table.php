<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sub_account_id')->unsigned()->nullable()->index();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('unsubscribed')->default(0);
            $table->boolean('marked')->default(false);
            $table->date('sub_date');
            $table->string('country_name')->nullable();
            $table->uuid('form_id')->index()->nullable();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('set null');
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
        Schema::dropIfExists('contacts');
    }
}
