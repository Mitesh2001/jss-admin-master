<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplineIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_individual', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned()->index()->nullable();
            $table->integer('discipline_id')->unsigned()->index()->nullable();
            $table->boolean('is_lifetime_member')->default(0);
            $table->date('registered_at')->nullable();
            $table->date('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('individual_id')
                ->references('id')
                ->on('individuals')
            ;

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
        Schema::dropIfExists('discipline_individual_membership');
    }
}
