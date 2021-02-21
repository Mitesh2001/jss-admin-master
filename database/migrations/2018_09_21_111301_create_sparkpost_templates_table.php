<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparkpostTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sparkpost_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('template_id');
            $table->integer('email_type_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('email_type_id')->references('id')->on('email_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sparkpost_templates');
    }
}
