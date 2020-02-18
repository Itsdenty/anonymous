<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSequenceEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequence_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('subject')->nullable();
            $table->longText('content')->nullable();
            $table->enum('send_time', ['now', 'delay'])->default('now');
            $table->integer('time_value')->default(0);
            $table->string('time_unit')->nullable();
            $table->integer('time_in_seconds')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->unsignedInteger('sequence_id')->nullable();
            $table->unsignedInteger('sort_id')->nullable();
            $table->timestamps();
            $table->foreign('sequence_id')->references('id')->on('sequences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sequence_emails');
    }
}
