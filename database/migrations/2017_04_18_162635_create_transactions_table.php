<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no');
            $table->integer('client_id');
            $table->string('transaction_type');
            $table->float('discount', 11, 2)->default(0);
            $table->float('total', 11, 2);
            $table->float('labor_cost', 11, 2)->default(0);
            $table->float('paid', 11, 2);
            $table->boolean('return')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
