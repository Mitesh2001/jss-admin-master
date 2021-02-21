<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->unsigned();
            $table->integer('type_id')->unsigned()->nullable();
            $table->decimal('amount')->unsigned();
            $table->string('notes')->nullable();
            $table->string('stripe_transfer_no')->nullable();
            $table->decimal('transaction_fee', 8, 2)->unsigned()->nullable();
            $table->datetime('paid_at');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('payment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_payments');
    }
}
