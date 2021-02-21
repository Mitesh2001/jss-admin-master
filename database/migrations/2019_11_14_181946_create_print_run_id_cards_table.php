<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintRunIdCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('print_run')->dropIfExists('print_run_id_cards');

        Schema::connection('print_run')->create('print_run_id_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('card_id');
            $table->string('full_name')->nullable();
            $table->string('membership_number')->nullable();
            $table->string('member_since')->nullable();
            $table->string('discipline_list')->nullable();
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
        Schema::connection('print_run')->dropIfExists('print_run_id_cards');
    }
}
