<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualRenewalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_renewals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned()->nullable();
            $table->integer('parent_renewal_id')->unsigned()->nullable()->comment('The individual_renewal_id of the 1st family member renewal submission.');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('gender_id')->unsigned()->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->integer('suburb_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('post_code')->unsigned()->nullable();
            $table->string('membership_no')->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->decimal('membership_price')->unsigned()->nullable();
            $table->date('ssaa_expiry')->nullable();
            $table->decimal('amount')->unsigned()->nullable();
            $table->decimal('discount')->unsigned()->nullable();
            $table->boolean('payment_type')->default(0)->nullable()->comment('0: 2nd or 3rd family member | 1: Offline | 2:Online');
            $table->string('transaction_no')->nullable();
            $table->decimal('received_amount')->unsigned()->nullable();
            $table->string('renewal_applier_full_name')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('individual_id')->references('id')->on('individuals');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->foreign('suburb_id')->references('id')->on('suburbs');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('parent_renewal_id')->references('id')->on('individual_renewals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individual_renewals');
    }
}
