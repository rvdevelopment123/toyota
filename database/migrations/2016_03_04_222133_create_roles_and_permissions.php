<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('permissions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name');
        });

        Schema::create('role_user', function(Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
        });

        Schema::create('role_permission', function(Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('role_permission');
    }
}
