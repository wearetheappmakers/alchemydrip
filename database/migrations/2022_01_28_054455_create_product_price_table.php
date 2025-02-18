<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('gender_id')->unsigned()->nullable();
            $table->integer('size_id')->unsigned()->nullable();
            $table->double('price', 8, 2);
            $table->double('wholesale_price', 8, 2)->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('gender')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('size')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price');
    }
}
