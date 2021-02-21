<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaptainDisciplineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captain_discipline', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('captain_id')->unsigned()->index()->nullable();
            $table->integer('discipline_id')->unsigned()->index()->nullable();
            $table->timestamps();

            $table->foreign('captain_id')->references('id')->on('users');
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
        Schema::dropIfExists('captain_discipline');
    }
}
