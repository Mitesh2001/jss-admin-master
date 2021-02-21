<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_runs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period')->nullable();
            $table->date('payment_due_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('status')->default(0)->comment('0: Inactive 1: Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewal_runs');
    }
}
