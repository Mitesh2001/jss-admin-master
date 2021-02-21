<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualSsaasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_ssaas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned();
            $table->integer('ssaa_number')->nullable();
            $table->boolean('ssaa_status')->default(0);
            $table->date('ssaa_expiry')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('individual_ssaas');
    }
}
