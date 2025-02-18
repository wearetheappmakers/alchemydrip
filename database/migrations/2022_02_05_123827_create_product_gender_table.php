<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_gender', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('gender_id')->unsigned();
            
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('gender')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_gender');
    }
}
