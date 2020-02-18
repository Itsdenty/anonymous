<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactCsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_csvs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('csv_filename');
            $table->boolean('csv_header')->default(0);
            $table->longText('csv_data');
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
        Schema::dropIfExists('contact_csvs');
    }
}
