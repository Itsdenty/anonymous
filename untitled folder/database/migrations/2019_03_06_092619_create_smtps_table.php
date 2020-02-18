<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smtps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('smtp_type_id')->unsigned()->nullable();
            $table->string('server');
            $table->string('port');
            $table->string('user');
            $table->string('password');
            $table->string('api_key')->nullable();
            $table->string('smtp_domain')->nullable();
            $table->string('domain')->nullable();
            $table->boolean("is_limited");
            $table->integer('sending_limit')->nullable();
            $table->integer('time_value')->nullable();
            $table->string('time_unit')->nullable();
            $table->integer('time_in_seconds')->nullable();
            $table->unsignedInteger('sub_account_id')->nullable();
            $table->string('encryption')->default('null');
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
        Schema::dropIfExists('smtps');
    }
}
