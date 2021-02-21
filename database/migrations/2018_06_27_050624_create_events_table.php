<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->text('comments')->nullable();
            $table->datetime('happened_at')->nullable();
            $table->timestamps();

            $table->foreign('individual_id')->references('id')->on('individuals');
            $table->foreign('type_id')->references('id')->on('event_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
