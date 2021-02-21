<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned();
            $table->string('membership_number')->nullable();
            $table->date('join_date')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('type_id')->nullable()->unsigned()->index();
            $table->text('notes')->nullable();
            $table->date('expiry')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('individual_id')->references('id')->on('individuals');
            $table->foreign('type_id')->references('id')->on('membership_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individual_memberships');
    }
}
