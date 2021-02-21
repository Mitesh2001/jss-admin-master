<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->date('event_date');
            $table->boolean('score_type')->default(0)->comment('1:Point based, 2:Deviation based');
            $table->integer('discipline_id')->unsigned();
            $table->boolean('is_finalised')->default(0);
            $table->boolean('is_attendance_tracked')->default(0);
            $table->boolean('is_public')->default(0);
            $table->time('start_time')->nullable();
            $table->string('title', 25)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('discipline_id')->references('id')->on('disciplines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}
