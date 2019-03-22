<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsAndTotalInstallmentToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function(Blueprint $table) {
            $table->float('upfront_payment', 11, 2)->after('client_id')->nullable();
            $table->float('monthly_payment', 11, 2)->after('upfront_payment')->nullable();
            $table->float('last_payment', 11, 2)->after('monthly_payment')->nullable();
            $table->float('total_installment', 11, 2)->after('last_payment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function(Blueprint $table) {
            $table->dropColumn('upfront_payment');
            $table->dropColumn('monthly_payment');
            $table->dropColumn('last_payment');
            $table->dropColumn('total_installment');
        });
    }
}
