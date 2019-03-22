<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceTaxToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings',function(Blueprint $table){
            $table->boolean('invoice_tax')->default(0)->after('product_tax');
            $table->float('invoice_tax_rate', 11, 2)->default(0)->after('invoice_tax');
            $table->integer('invoice_tax_type')->default(2)->after('invoice_tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings',function(Blueprint $table){
            $table->dropColumn('invoice_tax');
            $table->dropColumn('invoice_tax_rate');
            $table->dropColumn('invoice_tax_type');
        });
    }
}
