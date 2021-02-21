<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRangeOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_officers', function (Blueprint $table) {
            $table->id();
            $table->integer('individual_id')->unsigned()->index();
            $table->integer('discipline_id')->unsigned()->index();
            $table->date('added_date');
            $table->timestamps();

            $table->foreign('individual_id')->references('id')->on('individuals');
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
        Schema::dropIfExists('range_officers');
    }
}
