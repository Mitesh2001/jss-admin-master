<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('individual_id')->unsigned()->index();
            $table->boolean('key_type')->default(1)->comment('1:General 2:Committee');
            $table->integer('key_number');
            $table->date('issued_at');
            $table->decimal('deposit_amount', 8, 2);
            $table->date('returned_at')->nullable();
            $table->date('loosed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('keys');
    }
}
