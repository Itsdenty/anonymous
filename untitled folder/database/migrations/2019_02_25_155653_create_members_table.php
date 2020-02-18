<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email', 100)->unique();
            $table->integer('role')->default(1);
            $table->boolean('confirmed')->default(0);
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->unsignedInteger('leader_user_id')->nullable();
            $table->string('token', 16)->unique()->nullable();
            $table->foreign('leader_user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('members');
    }
}
