<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_event_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_event_id')->unsigned();
            $table->integer('individual_id')->unsigned();
            $table->decimal('score', 14, 8)->unsigned();
            $table->boolean('score_unit')->unsigned()->nullable()->comment('1:mm, 2:inch');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('calendar_event_id')->references('id')->on('calendar_events');
            $table->foreign('individual_id')->references('id')->on('individuals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_event_scores');
    }
}
