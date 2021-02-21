<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('first_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('gender_id')->unsigned()->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->integer('suburb_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->string('post_code')->nullable();
            $table->boolean('pension_card')->default(0);
            $table->boolean('is_committee_member')->default(0);
            $table->boolean('is_club_lifetime_member')->default(0);
            $table->integer('branch_code_id')->unsigned()->nullable();
            $table->string('wwc_card_number')->nullable();
            $table->date('wwc_expiry_date')->nullable();
            $table->integer('family_id')->unsigned()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('gender_id')->references('id')->on('genders');
            $table->foreign('suburb_id')->references('id')->on('suburbs');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('branch_code_id')->references('id')->on('branch_codes');
            $table->foreign('family_id')->references('id')->on('families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individuals');
    }
}
