<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned()->nullable();
            $table->integer('individual_renewal_id')->unsigned()->nullable();
            $table->integer('renewal_run_id')->unsigned()->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('pending')->default(false);
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->boolean('confirmation_email_queued')->default(false);
            $table->boolean('confirmation_emailed')->default(false);
            $table->boolean('card_print_status')->default(1)->comment('1:Not exported,2:Exported,3:Printed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('individual_id')->references('id')->on('individuals');
            $table->foreign('individual_renewal_id')->references('id')->on('individual_renewals');
            $table->foreign('renewal_run_id')->references('id')->on('renewal_runs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewals');
    }
}
