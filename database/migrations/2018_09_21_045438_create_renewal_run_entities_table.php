<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalRunEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_run_entities', function (Blueprint $table) {
            $table->integer('renewal_run_id')->unsigned();
            $table->integer('individual_id')->unsigned();
            $table->timestamps();

            $table->primary(['renewal_run_id', 'individual_id']);
            $table->foreign('renewal_run_id')->references('id')->on('renewal_runs');
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
        Schema::dropIfExists('renewal_run_entities');
    }
}
