<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirearmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firearms', function (Blueprint $table) {
            $table->id();
            $table->integer('firearm_type_id')->unsigned()->index();
            $table->string('make');
            $table->string('model');
            $table->string('calibre');
            $table->string('serial');
            $table->integer('discipline_id')->unsigned()->index();
            $table->date('support_granted_at');
            $table->date('support_removed_at')->nullable();
            $table->date('mark_as_disposed_at')->nullable();
            $table->text('support_reason')->nullable();
            $table->text('disposed_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firearms');
    }
}
