<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_child', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_id')->unsigned();
            $table->foreign('so_id')->references('id')->on('so')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->integer('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('school')->onDelete('cascade');
            $table->integer('size_id')->unsigned();
            $table->foreign('size_id')->references('id')->on('size')->onDelete('cascade');
            $table->integer('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('gender')->onDelete('cascade');
            $table->string('qty');
            $table->string('price');
            $table->string('wholesale_Price');
            $table->string('amount');
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
        Schema::dropIfExists('so_child');
    }
}
