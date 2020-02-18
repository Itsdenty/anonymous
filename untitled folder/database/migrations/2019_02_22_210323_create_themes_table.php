<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('form_type');
            $table->string('poll_type')->nullable();
            $table->longText('content');
            $table->string('background_color')->nullable();
            $table->string('background_overlay')->nullable();
            $table->string('button_text_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('border_style')->nullable();
            $table->string('border_color')->nullable();
            $table->string('border_size')->nullable();
            $table->integer('button_font_size')->default(16)->nullable();
            $table->string('button_font_family')->default('Arial')->nullable();
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
        Schema::dropIfExists('themes');
    }
}
