<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirearmIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firearm_individual', function (Blueprint $table) {
            $table->bigInteger('firearm_id')->unsigned()->index();
            $table->integer('individual_id')->unsigned()->index();

            $table->foreign('firearm_id')->references('id')->on('firearms');
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
        Schema::dropIfExists('firearm_individual');
    }
}
