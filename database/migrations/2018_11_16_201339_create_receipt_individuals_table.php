<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->integer('individual_id')->unsigned()->nullable();
            $table->boolean('is_payer')->default(1)->comment('1:Receipt Payer, 0: Other member');

            $table->foreign('receipt_id')->references('id')->on('receipts');
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
        Schema::dropIfExists('receipt_individuals');
    }
}
