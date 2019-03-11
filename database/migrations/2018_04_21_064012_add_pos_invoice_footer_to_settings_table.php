<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosInvoiceFooterToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings',function(Blueprint $table){
            $table->string('pos_invoice_footer_text')->after('vat_no')->nullable();
            $table->string('dashboard')->after('pos_invoice_footer_text')->default('chart-box');
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
            $table->dropColumn('pos_invoice_footer_text');
            $table->dropColumn('dashboard');
        });
    }
}
