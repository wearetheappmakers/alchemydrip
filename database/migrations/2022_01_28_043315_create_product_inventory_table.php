<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('gender_id')->unsigned();
            $table->integer('size_id')->unsigned();
            $table->integer('min_order_qty');
            $table->integer('max_order_qty');
            $table->integer('inventory');
            $table->integer('used')->default(0);

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('gender')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('size')->onDelete('cascade');
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
        Schema::dropIfExists('product_inventory');
    }
}
