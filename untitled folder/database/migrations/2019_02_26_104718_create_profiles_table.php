<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->unsigned();
            $table->longText("address");
            $table->integer("country_id");
            $table->string("state");
            $table->string("city");
            $table->string("zip_code")->nullable();
            $table->integer("time_zone_id")->unsigned();
            $table->timestamps();
            
            // $table->foreign('country_id')->references('id')->on('countries');
            // $table->foreign('time_zone_id')->references('id')->on('time_zones');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
