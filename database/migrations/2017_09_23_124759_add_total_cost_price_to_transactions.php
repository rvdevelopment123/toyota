<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalCostPriceToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions',function(Blueprint $table){
            $table->double('total_cost_price', 11, 2)->nullable()->after('transaction_type');
            $table->float('invoice_tax', 11, 2)->nullable()->after('total');
            $table->float('total_tax', 11, 2)->nullable()->after('invoice_tax');
            $table->float('net_total', 11, 2)->nullable()->after('labor_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions',function(Blueprint $table){
            $table->dropColumn('total_cost_price');
            $table->dropColumn('invoice_tax');
            $table->dropColumn('total_tax');
            $table->dropColumn('net_total');
        });
    }
}
