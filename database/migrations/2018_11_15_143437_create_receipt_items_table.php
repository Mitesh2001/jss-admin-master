<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->unsigned();
            $table->integer('discipline_id')->unsigned()->nullable()->comment('NULL:For membership,n:For Discipline');
            $table->integer('receipt_item_code_id')->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount')->default(0);
            $table->timestamps();

            $table->foreign('receipt_id')->references('id')->on('receipts');
            $table->foreign('discipline_id')->references('id')->on('disciplines');
            $table->foreign('receipt_item_code_id')->references('id')->on('receipt_item_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_items');
    }
}
