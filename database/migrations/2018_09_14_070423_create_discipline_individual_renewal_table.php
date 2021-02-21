<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplineIndividualRenewalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_individual_renewal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_renewal_id')->unsigned()->index()->nullable();
            $table->integer('discipline_id')->unsigned()->index()->nullable();
            $table->integer('individual_id')->unsigned()->index()->nullable();
            $table->boolean('is_lifetime_member')->default(0);
            $table->decimal('price')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('individual_renewal_id')->references('id')->on('individual_renewals');
            $table->foreign('discipline_id')->references('id')->on('disciplines');
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
        Schema::dropIfExists('discipline_individual_renewal');
    }
}
