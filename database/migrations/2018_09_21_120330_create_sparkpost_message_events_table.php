<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparkpostMessageEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sparkpost_message_events', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->dateTime('injection_time')->nullable();
            $table->integer('individual_id')->unsigned()->nullable();
            $table->string('rcpt_to')->nullable();
            $table->string('subject')->nullable();
            $table->string('template_id')->nullable();
            $table->bigInteger('transmission_id')->unsigned()->nullable();
            $table->dateTime('timestamp')->nullable();
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
        Schema::dropIfExists('sparkpost_message_events');
    }
}
