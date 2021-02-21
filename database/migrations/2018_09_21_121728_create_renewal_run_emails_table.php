<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalRunEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_run_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renewal_run_id')->unsigned();
            $table->integer('sparkpost_template_id')->unsigned();
            $table->integer('individual_id')->unsigned()->nullable();
            $table->datetime('sent_at');
            $table->timestamps();

            $table->foreign('renewal_run_id')->references('id')->on('renewal_runs');
            $table->foreign('sparkpost_template_id')->references('id')->on('sparkpost_templates');
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
        Schema::dropIfExists('renewal_run_emails');
    }
}
