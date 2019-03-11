<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThemesToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings',function(Blueprint $table){
            $table->string('theme')->default("bg-gradient-9")->after('invoice_tax_rate');
            $table->string('vat_no')->after('invoice_tax_type')->nullable();
            $table->boolean('enable_purchaser')->default(1)->after('theme');
            $table->boolean('enable_customer')->default(1)->after('enable_purchaser');
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
            $table->dropColumn('theme');
            $table->dropColumn('vat_no');
            $table->dropColumn('enable_purchaser');
            $table->dropColumn('enable_customer');
        });
    }
}
