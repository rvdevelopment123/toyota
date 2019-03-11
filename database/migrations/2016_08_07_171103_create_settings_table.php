<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name');
            $table->string('slogan')->nullable();
            $table->string('address');
            $table->string('email', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('site_logo')->nullable();
            $table->string('currency_code')->nullable();
            $table->integer('alert_quantity')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
