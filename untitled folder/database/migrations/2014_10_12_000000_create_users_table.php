<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('current_sub_account_id')->unsigned()->index()->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('role_id')->unsigned()->default(2);
            $table->boolean('double_optin')->default(0);
            $table->boolean('gdpr')->default(0);
            $table->integer('leader_id')->unsigned()->nullable();
            $table->foreign('leader_id')->references('id')->on('users')->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
