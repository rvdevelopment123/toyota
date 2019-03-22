<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function($table){
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->integer('category_id');
            $table->integer('subcategory_id')->nullable();           
            $table->float('quantity')->nullable();
            $table->longtext('details')->nullable();
            $table->float('cost_price', 11, 2);
            $table->float('mrp', 11, 2);
            $table->float('minimum_retail_price', 11 ,2)->nullable();
            $table->string('unit', 11)->nullable();
            $table->boolean('status')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('products');
    }
}
