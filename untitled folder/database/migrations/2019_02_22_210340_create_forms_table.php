<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('title');
            $table->string('form_type');
            $table->longText('headline')->nullable();
            $table->longText('description')->nullable();
            $table->longText('foot_note')->nullable();
            $table->longText('success_headline')->nullable();
            $table->longText('success_description')->nullable();
            $table->string('background_color')->nullable();
            $table->string('background_overlay')->nullable();
            $table->string('button_text_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('position')->nullable();
            $table->string('size')->nullable();
            $table->string('email_label')->nullable();
            $table->string('email_placeholder')->nullable();
            $table->boolean('show_phone_field')->default(0);
            $table->string('phone_label')->nullable();
            $table->string('phone_placeholder')->nullable();
            $table->string('button_text')->nullable();
            $table->string('trigger_point')->nullable();
            $table->integer('hide_duration')->nullable();
            $table->integer('loading_delay')->default(3)->nullable();
            $table->integer('frequency')->nullable();
            $table->boolean('autohide')->default(0);
            $table->boolean('page_load')->default(0);
            $table->boolean('page_exit')->default(0);
            $table->boolean('first_visit')->default(0);
            $table->boolean('allow_closing')->default(0);
            $table->string('redirect_url')->nullable();
            $table->boolean('desktop_device')->default(0);
            $table->boolean('mobile_device')->default(0);
            $table->boolean('tablet_device')->default(0);
            $table->string('logo_upload')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('background_image')->nullable();
            $table->string('poll_type')->nullable();
            $table->integer('button_font_size')->default(16)->nullable();
            $table->string('button_font_family')->default('Arial')->nullable();
            $table->integer('option_font_size')->default(16)->nullable();
            $table->string('option_font_family')->default('Arial')->nullable();
            $table->string('option_color')->default('#000000')->nullable();
            $table->string('border_style')->nullable();
            $table->string('border_color')->nullable();
            $table->string('border_size')->nullable();
            $table->longText('tracking_pixel')->nullable();
            $table->boolean('is_template')->default(0);
            $table->unsignedInteger('sub_account_id')->nullable();
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');
            $table->unsignedInteger('theme_id')->nullable();
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
        Schema::dropIfExists('forms');
    }
}
