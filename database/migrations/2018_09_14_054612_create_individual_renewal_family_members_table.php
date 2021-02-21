<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualRenewalFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_renewal_family_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_renewal_id')->unsigned()->index()->nullable();
            $table->integer('family_member_id')->unsigned()->index()->nullable();
            $table->boolean('is_pensioner')->default(0);
            $table->boolean('is_club_lifetime_member')->default(0);
            $table->boolean('is_committee_member')->default(0);
            $table->timestamps();

            $table->foreign('individual_renewal_id')
                ->references('id')
                ->on('individual_renewals')
            ;
            $table->foreign('family_member_id')
                ->references('id')
                ->on('individuals')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individual_renewal_family_members');
    }
}
