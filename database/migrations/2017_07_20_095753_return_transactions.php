<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReturnTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sells_id');
            $table->integer('client_id');
            $table->float('return_vat');
            $table->string('sells_reference_no');
            $table->integer('return_units');
            $table->float('return_amount');
            $table->integer('returned_by');
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
        Schema::dropIfExists('return_transactions');
    }
}
