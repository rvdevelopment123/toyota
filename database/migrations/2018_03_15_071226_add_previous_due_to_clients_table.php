<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreviousDueToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients',function(Blueprint $table){
            $table->float('provious_due')->after('client_type')->nullable();
            $table->string('account_no')->after('provious_due')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients',function(Blueprint $table){
            $table->dropColumn('provious_due');
            $table->dropColumn('account_no');
        });
    }
}
