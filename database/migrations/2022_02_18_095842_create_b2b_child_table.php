<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_child', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('b2b_id')->unsigned();
            $table->foreign('b2b_id')->references('id')->on('b2b')->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->integer('qty');
            $table->integer('amount');
            $table->integer('gst')->default(0);
            $table->integer('gst_amount')->default(0);
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('b2b_child');
    }
}
